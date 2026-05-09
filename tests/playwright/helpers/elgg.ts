import { Page, expect } from '@playwright/test';
import mysql from 'mysql2/promise';

const DB_CONFIG = {
  host: process.env.ELGG_DB_HOST || 'db',
  port: Number(process.env.ELGG_DB_PORT || 3306),
  user: process.env.ELGG_DB_USER || 'elgg',
  password: process.env.ELGG_DB_PASS || 'elgg',
  database: process.env.ELGG_DB_NAME || 'elgg',
};

export async function loginAs(
  page: Page,
  username: string,
  password: string = 'testpass123'
) {
  await page.goto('/login');
  await page.fill('input[name="username"]', username);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
  await page.waitForURL(/\//);
}

export async function queryDb(sql: string, params: any[] = []): Promise<any[]> {
  const conn = await mysql.createConnection(DB_CONFIG);
  const [rows] = await conn.execute(sql, params);
  await conn.end();
  return rows as any[];
}

export async function getUserByUsername(username: string) {
  const rows = await queryDb(
    `SELECT e.*
     FROM elgg_entities e
     JOIN elgg_metadata m ON m.entity_guid = e.guid AND m.name = 'username'
     WHERE m.value = ?`,
    [username]
  );
  return rows[0];
}

export async function getMetadata(entityGuid: number, name: string) {
  return queryDb(
    'SELECT * FROM elgg_metadata WHERE entity_guid = ? AND name = ?',
    [entityGuid, name]
  );
}

export async function getRelationship(
  guid_one: number,
  relationship: string,
  guid_two: number
) {
  return queryDb(
    `SELECT * FROM elgg_entity_relationships
     WHERE guid_one = ? AND relationship = ? AND guid_two = ?`,
    [guid_one, relationship, guid_two]
  );
}

export async function getRelationshipsByType(
  guid_one: number,
  relationship: string
) {
  return queryDb(
    `SELECT * FROM elgg_entity_relationships
     WHERE guid_one = ? AND relationship = ?`,
    [guid_one, relationship]
  );
}

export async function getPrivateSetting(entityGuid: number, name: string) {
  return queryDb(
    'SELECT * FROM elgg_private_settings WHERE entity_guid = ? AND name = ?',
    [entityGuid, name]
  );
}
