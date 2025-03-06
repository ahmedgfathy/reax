import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

// Function to extract translation strings from PHP files
const extractFromBladeFiles = (directory) => {
    const translations = {};
    
    // Recursive function to read all files in directory
    const readDir = (dir) => {
        const files = fs.readdirSync(dir);
        
        files.forEach(file => {
            const filePath = path.join(dir, file);
            const stats = fs.statSync(filePath);
            
            if (stats.isDirectory()) {
                readDir(filePath);
            } else if (file.endsWith('.blade.php')) {
                const content = fs.readFileSync(filePath, 'utf8');
                const matches = content.match(/\{\{__\(['"`](.+?)['"`]\)\}\}/g);
                
                if (matches) {
                    matches.forEach(match => {
                        const translationKey = match.match(/\{\{__\(['"`](.+?)['"`]\)\}\}/)[1];
                        translations[translationKey] = translationKey;
                    });
                }
            }
        });
    };
    
    readDir(directory);
    return translations;
};

// Extract translations
const translations = extractFromBladeFiles(path.join(__dirname, 'resources/views'));

// Write to language files
const langDir = path.join(__dirname, 'resources/lang');

// Ensure the lang directory exists
if (!fs.existsSync(langDir)){
    fs.mkdirSync(langDir);
}

// Update en.json
const enPath = path.join(langDir, 'en.json');
fs.writeFileSync(enPath, JSON.stringify(translations, null, 4));

console.log(`Extracted ${Object.keys(translations).length} translations to ${enPath}`);
console.log('You can now edit ar.json with translations for these keys.');
