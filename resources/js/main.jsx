import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './app.jsx';
import { AuthProvider } from './context/AuthContext';

const root = createRoot(document.getElementById('root'));
root.render(
  <AuthProvider>
    <App />
  </AuthProvider>
);
