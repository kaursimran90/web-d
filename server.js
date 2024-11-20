const express = require('express');
const mysql = require('mysql2');
const path = require('path');
const multer = require('multer');

const app = express();
const port = 3000;

// Configure MySQL connection
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'nails_hub_cms' //database name
});

// Connect to MySQL
db.connect((err) => {
  if (err) {
    console.error("Database connection failed:", err.message);
    return;
  }
  console.log("Connected to MySQL Database");
});

// Configure multer for file uploads
const uploadDir = path.join(__dirname, 'uploads');
const storage = multer.diskStorage({
  destination: uploadDir,
  filename: (req, file, cb) => {
    const uniqueName = Date.now() + path.extname(file.originalname);
    cb(null, uniqueName);
  }
});
const upload = multer({ storage: storage });

// POST route to handle image and data upload
app.post('/upload', upload.single('image'), (req, res) => {
  // Retrieve form data
  const { name, description, price } = req.body;
  const imageUrl = `/uploads/${req.file.filename}`;

  // Insert data into MySQL
  const query = `INSERT INTO services (name, description, price, image) VALUES (?, ?, ?, ?)`;
  db.query(query, [name, description, price, imageUrl], (err, result) => {
    if (err) {
      console.error("Database error:", err.message);
      return res.status(500).send("Failed to save data to database");
    }
    // Redirect back to ad.php after successful upload
    res.redirect('https://localhost/cms_project/Test/ad.php');
  });
});

// Serve static files for uploaded images
app.use('/uploads', express.static(uploadDir));

// Start the server
app.listen(port, () => {
  console.log(`Server running on http://localhost:${port}`);
});
