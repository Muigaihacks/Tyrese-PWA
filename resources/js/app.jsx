import React from 'react';
import { createRoot } from 'react-dom/client';

const container = document.getElementById('app');
const root = createRoot(container);
root.render(
    <div className="bg-blue-500 text-white p-4">
        Testing Tailwind CSS
    </div>
);
