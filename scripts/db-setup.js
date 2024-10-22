import { config } from 'dotenv';
import fs from 'fs';
import mysql from 'mysql2/promise';
import xml2js from 'xml2js';
import chalk from 'chalk';

config();

const createConnection = async () => {
  try {
    return await mysql.createConnection({
      host: 'mariadb',
      user: process.env.MYSQL_USER,
      password: process.env.MYSQL_PASSWORD,
      database: process.env.MYSQL_DATABASE
    });
  } catch (error) {
    console.error(chalk.red('Prišlo je do napake pri povezavi s podatkovno bazo:'), error.message);

    throw error;
  }
};

const createAgentsTable = async (connection) => {
  const createSitesTableQuery = `
    CREATE TABLE IF NOT EXISTS agents (
      id INT AUTO_INCREMENT PRIMARY KEY,
      first_name VARCHAR(100) NOT NULL,
      last_name VARCHAR(100) NOT NULL
    );
  `;

  try {
    await connection.query(createSitesTableQuery);

    console.log(chalk.green('Tabela "agents" uspešno ustvarjena.'));
  } catch (error) {
    console.error(chalk.red('Prišlo je do napake pri kreiranju "agents" tabele:'), error.message);

    throw error;
  }  
};

const createSitesTable = async (connection) => {
  const createSitesTableQuery = `
    CREATE TABLE IF NOT EXISTS sites (
      id INT AUTO_INCREMENT PRIMARY KEY,
      site VARCHAR(255) NOT NULL,
      latitude DECIMAL(10, 8) NOT NULL,
      longitude DECIMAL(11, 8) NOT NULL,
      agent_id INT,
      FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL
    );
  `;

  try {
    await connection.query(createSitesTableQuery);

    console.log(chalk.green('Tabela "sites" uspešno ustvarjena.'));
  } catch (error) {
    console.error(chalk.red('Prišlo je do napake pri kreiranju "sites" tabele:'), error.message);

    throw error;
  }
};

const parseNames = async () => {
  try {
    const firstNamesData = await fs.promises.readFile('datasets/first-names.txt', 'utf-8');
    const lastNamesData = await fs.promises.readFile('datasets/last-names.txt', 'utf-8');

    const firstNames = firstNamesData.split('\n').map(name => name.trim()).filter(name => name);
    const lastNames = lastNamesData.split('\n').map(name => name.trim()).filter(name => name);

    return { firstNames, lastNames };
  } catch (error) {
    console.error('Napaka pri branju datotek:', error.message);

    throw error;
  }
};

const parseSites = async () => {
  const parser = new xml2js.Parser();
  const data = await fs.promises.readFile('datasets/unesco-world-heritage-sites.xml');
  const result = await parser.parseStringPromise(data);

  if (!result.query || !result.query.row) {
    throw new Error('Neveljavna struktura XML datoteke.');
  }

  return result.query.row;
};

const fillTables = async (firstNames, lastNames, sites, connection) => {

  if (firstNames.length < sites.length || lastNames.length < sites.length) {
    throw new Error('Ni dovolj unikatnih imen ali priimkov.');
  }

  try {
    await connection.beginTransaction();

    const insertQueries = sites.map(site => {
      const firstNameIndex = Math.floor(Math.random() * firstNames.length);
      const firstName = firstNames[firstNameIndex];
      firstNames.splice(firstNameIndex, 1);

      const lastNameIndex = Math.floor(Math.random() * lastNames.length);
      const lastName = lastNames[lastNameIndex];
      lastNames.splice(lastNameIndex, 1);

      const agentQuery = `
        INSERT INTO agents (first_name, last_name)
        VALUES (?, ?)
      `;

      const insertAgentPromise = connection.query(agentQuery, [firstName, lastName]);

      const siteName = site.site[0]; 
      const latitude = parseFloat(site.latitude[0]);
      const longitude = parseFloat(site.longitude[0]);
  
      const siteQuery = `
        INSERT INTO sites (site, latitude, longitude, agent_id)
        VALUES (?, ?, ?, LAST_INSERT_ID())
      `;

      const insertSitePromise = connection.query(siteQuery, [siteName, latitude, longitude]);
  
      return Promise.all([insertAgentPromise, insertSitePromise]);
    });

    await Promise.all(insertQueries);

    await connection.commit();

    console.log(chalk.green('Podatki so uspešno vneseni v ustrezne tabele.'));
  } catch (error) {
    await connection.rollback();

    console.error(chalk.red('Napaka pri vstavljanju podatkov:'), error.message);

    throw error;
  }
};

const setup = async () => {
  let connection;

  try {
    connection = await createConnection();
    console.log(chalk.green('Povezava s podatkovno bazo uspešna.'));

    await createAgentsTable(connection);
    await createSitesTable(connection);

    const { firstNames, lastNames } = await parseNames();
    const sites = await parseSites();

    await fillTables(firstNames, lastNames, sites, connection);
  } catch (error) {
    console.error(chalk.red('Napaka med procesom:'), error.message);
  } finally {
    if (connection) {
      await connection.end();

      console.log(chalk.green('Povezava z bazo uspešno zaprta.'));
    }
  }
};


setup();
