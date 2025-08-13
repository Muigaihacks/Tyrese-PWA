import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import DashboardLayout from './components/DashboardLayout';
import Login from './components/Auth/Login';
import ResetPassword from './components/Auth/ResetPassword';
import ForgotPassword from './components/Auth/ForgotPassword';
import Inventory from './pages/Inventory';
import ClientStorage from './pages/ClientStorage';
import Map from './pages/Map';
import CasualLabourerModule from './components/CasualLabourerModule';
import CrateTrackerModule from './components/CrateTrackerModule';
import ProtectedRoute from './components/ProtectedRoute';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/password-reset" element={<ResetPassword />} />
        <Route path="/password-reset/:token" element={<ResetPassword />} />
        
        <Route path="/" element={
          <ProtectedRoute>
            <DashboardLayout />
          </ProtectedRoute>
        }>
          <Route index element={<Inventory />} />
          <Route path="inventory" element={<Inventory />} />
          <Route path="storage" element={<ClientStorage />} />
          <Route path="map" element={<Map />} />
          <Route path="client-storage" element={<ClientStorage />} />
          <Route path="casual-labourer" element={<CasualLabourerModule />} />
          <Route path="crate-tracker" element={<CrateTrackerModule />} />
        </Route>
      </Routes>
    </Router>
  );
}

export default App;

