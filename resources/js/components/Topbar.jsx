import React, { useState } from "react";
import { useAuth } from "../context/AuthContext";
import axios from "axios";

export default function Topbar() {
  const { user, logout } = useAuth();
  const [showPasswordModal, setShowPasswordModal] = useState(false);
  const [passwordData, setPasswordData] = useState({
    current_password: "",
    new_password: "",
    confirm_password: "",
  });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");

  const handlePasswordChange = async (e) => {
    e.preventDefault();
    
    if (passwordData.new_password !== passwordData.confirm_password) {
      setError("New passwords do not match.");
      return;
    }

    if (passwordData.new_password.length < 8) {
      setError("New password must be at least 8 characters long.");
      return;
    }

    setLoading(true);
    setError("");
    setMessage("");

    try {
      // 1. Get CSRF cookie
      await axios.get('/sanctum/csrf-cookie', { withCredentials: true });

      // 2. Change password
      const response = await axios.post('/api/change-password', passwordData, {
        withCredentials: true,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        }
      });

      setMessage("Password changed successfully!");
      setPasswordData({
        current_password: "",
        new_password: "",
        confirm_password: "",
      });
      setTimeout(() => setShowPasswordModal(false), 2000);
    } catch (err) {
      console.error("Password change error:", err);
      if (err.response?.data?.message) {
        setError(err.response.data.message);
      } else {
        setError("Network error. Please try again.");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <div className="bg-white shadow-sm border-b px-4 py-3 flex justify-between items-center">
        <div className="flex items-center">
          <div className="w-8 h-8 bg-green-500 rounded mr-3"></div>
          <span className="font-semibold text-gray-800">Demo System</span>
        </div>
        
        <div className="flex items-center space-x-4">
          <span className="text-gray-600">
            {user?.name ? user.name.split(' ')[0] : "Guest"}
          </span>
          
          <div className="relative">
            <button
              onClick={() => setShowPasswordModal(true)}
              className="text-sm text-blue-600 hover:text-blue-800 mr-3"
            >
              Change Password
            </button>
            
            <button
              onClick={logout}
              className="text-sm text-red-600 hover:text-red-800"
            >
              Logout
            </button>
          </div>
        </div>
      </div>

      {/* Password Change Modal */}
      {showPasswordModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 className="text-lg font-semibold mb-4">Change Password</h3>
            
            {message && (
              <div className="mb-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded">
                {message}
              </div>
            )}
            
            {error && (
              <div className="mb-4 p-3 bg-red-100 border border-red-400 text-red-800 rounded">
                {error}
              </div>
            )}

            <form onSubmit={handlePasswordChange}>
              <div className="mb-3">
                <label className="block text-sm font-medium mb-1">
                  Current Password
                </label>
                <input
                  type="password"
                  value={passwordData.current_password}
                  onChange={(e) => setPasswordData({
                    ...passwordData,
                    current_password: e.target.value
                  })}
                  required
                  className="w-full border rounded px-3 py-2"
                />
              </div>

              <div className="mb-3">
                <label className="block text-sm font-medium mb-1">
                  New Password
                </label>
                <input
                  type="password"
                  value={passwordData.new_password}
                  onChange={(e) => setPasswordData({
                    ...passwordData,
                    new_password: e.target.value
                  })}
                  required
                  minLength={8}
                  className="w-full border rounded px-3 py-2"
                />
              </div>

              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">
                  Confirm New Password
                </label>
                <input
                  type="password"
                  value={passwordData.confirm_password}
                  onChange={(e) => setPasswordData({
                    ...passwordData,
                    confirm_password: e.target.value
                  })}
                  required
                  className="w-full border rounded px-3 py-2"
                />
              </div>

              <div className="flex justify-end space-x-3">
                <button
                  type="button"
                  onClick={() => setShowPasswordModal(false)}
                  className="px-4 py-2 text-gray-600 border rounded hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={loading}
                  className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400"
                >
                  {loading ? "Changing..." : "Change Password"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
} 