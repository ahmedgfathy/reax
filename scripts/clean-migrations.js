const fs = require('fs');
const path = require('path');

// Configuration
const migrationsDir = path.resolve(__dirname, '../database/migrations');

// Function to get file stats including creation date
function getFileStats(filepath) {
  const stats = fs.statSync(filepath);
  return {
    path: filepath,
    name: path.basename(filepath),
    birthtime: stats.birthtime,
    mtime: stats.mtime
  };
}

// Main function to clean up migrations
async function cleanMigrations() {
  console.log(`Scanning migrations directory: ${migrationsDir}`);
  
  // Read all files in the migrations directory
  const files = fs.readdirSync(migrationsDir);
  
  // Group files by their base name (ignoring timestamp prefixes if present)
  const fileGroups = {};
  
  files.forEach(filename => {
    // Common migration filename format: YYYYMMDDHHMMSS_name.js or timestamp_name.js
    // Extract the core name without timestamp
    let coreName;
    
    // Try to match timestamp_name pattern
    const match = filename.match(/^\d+_(.+)$/);
    if (match) {
      coreName = match[1];
    } else {
      coreName = filename;
    }
    
    if (!fileGroups[coreName]) {
      fileGroups[coreName] = [];
    }
    
    fileGroups[coreName].push(getFileStats(path.join(migrationsDir, filename)));
  });
  
  let deletedCount = 0;
  let keptCount = 0;
  
  // Process each group of files
  for (const [coreName, fileList] of Object.entries(fileGroups)) {
    if (fileList.length <= 1) {
      keptCount += 1;
      console.log(`Keeping single file: ${fileList[0].name}`);
      continue; // Skip if there's only one file
    }
    
    // Sort by creation date (newest first)
    fileList.sort((a, b) => b.birthtime - a.birthtime);
    
    // Keep the newest file
    const newestFile = fileList[0];
    console.log(`Keeping newest file: ${newestFile.name} (created: ${newestFile.birthtime})`);
    keptCount += 1;
    
    // Delete the rest (older duplicates)
    fileList.slice(1).forEach(file => {
      console.log(`Deleting older duplicate: ${file.name} (created: ${file.birthtime})`);
      fs.unlinkSync(file.path);
      deletedCount += 1;
    });
  }
  
  console.log(`\nMigration cleanup complete!`);
  console.log(`Files kept: ${keptCount}`);
  console.log(`Files deleted: ${deletedCount}`);
}

// Execute the cleanup
cleanMigrations().catch(err => {
  console.error('Error cleaning migrations:', err);
  process.exit(1);
});
