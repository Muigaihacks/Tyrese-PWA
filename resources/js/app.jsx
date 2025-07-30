import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import DashboardLayout from './components/DashboardLayout';
import Inventory from './pages/Inventory';
import ClientStorage from './pages/ClientStorage';
import Map from './pages/Map';
import Insurance from './pages/Insurance';
import ProtectedRoute from './components/ProtectedRoute';
import Login from './components/Auth/Login.jsx';
import ResetPassword from './components/Auth/ResetPassword';

export default function App() {
  return (
    <Router>
      <Routes>
        {/* Public route */}
        <Route path="/login" element={<Login />} />
        <Route path="/reset-password" element={<ResetPassword />} />
        <Route path="/password-reset/:token" element={<ResetPassword />} />

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

