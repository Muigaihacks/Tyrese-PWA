import axios from 'axios';

// Set default base URL for all requests - use relative URLs to avoid CORS issues
// axios.defaults.baseURL = 'http://localhost:8000'; // Your Laravel backend

// Set default credentials handling
axios.defaults.withCredentials = true;
