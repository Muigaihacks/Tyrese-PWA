import React from 'react';
import { createRoot } from 'react-dom/client';
import Login from './components/Auth/Login';

const container = document.getElementById('app');
const root = createRoot(container);
root.render(<Login />);
