// jest.config.js
module.exports = {
  // Solo busca tests en TU carpeta src/
  roots: ['<rootDir>/src'],

  // Patrón de archivos de test
  testMatch: ['**/__tests__/**/*.test.js'],

  // Ignora node_modules y extensiones externas
  testPathIgnorePatterns: [
    '/node_modules/',
    '/.vscode/',
    '/AppData/',
  ],

  // Cobertura solo de tus archivos
  collectCoverageFrom: ['src/**/*.js', '!src/**/__tests__/**'],
};