import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import DashboardLayout from './components/DashboardLayout';
import Inventory from './pages/Inventory';
import ClientStorage from './pages/ClientStorage';
import Map from './pages/Map';
import Insurance from './pages/Insurance';
import ProtectedRoute from './components/ProtectedRoute';
import Login from './components/Auth/Login.jsx';

export default function App() {
  return (
    <Router>
      <Routes>
        {/* Public route */}
        <Route path="/login" element={<Login />} />

        {/* Protected routes */}
        <Route
          path="/*"
          element={
            <ProtectedRoute>
              <DashboardLayout>
                <Routes>
                  <Route path="/inventory" element={<Inventory />} />
                  <Route path="/storage" element={<ClientStorage />} />
                  <Route path="/map" element={<Map />} />
                  <Route path="/insurance" element={<Insurance />} />
                  <Route path="*" element={<Navigate to="/inventory" replace />} />
                </Routes>
              </DashboardLayout>
            </ProtectedRoute>
          }
        />
      </Routes>
    </Router>
  );
}
