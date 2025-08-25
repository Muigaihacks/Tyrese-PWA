import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import axios from 'axios';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();
  const { login } = useAuth();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setIsLoading(true);

    try {
      // 1. Get CSRF cookie
      await axios.get('/sanctum/csrf-cookie', { withCredentials: true });

      // 2. Login (POST to /login)
      const loginResponse = await axios.post('/login', { email, password }, { withCredentials: true });
      
      // Check if login was successful
      if (loginResponse.status !== 200 && loginResponse.status !== 204) {
        throw new Error('Login failed');
      }

      // 3. Fetch user data only if login was successful
      const userResponse = await axios.get('/api/user', { withCredentials: true });
      const userData = userResponse.data;

      // 4. Only login and navigate if we have valid user data
      if (userData && userData.id) {
        login(userData);
        navigate('/inventory');
      } else {
        throw new Error('Invalid user data received');
      }
    } catch (err) {
      console.error('Login error:', err);
      
      // Handle different types of errors
      if (err.response) {
        // Server responded with error status
        if (err.response.status === 422) {
          // Validation error (wrong credentials)
          setError('Invalid email or password. Please check your credentials.');
        } else if (err.response.status === 429) {
          // Rate limiting
          setError('Too many login attempts. Please try again later.');
        } else if (err.response.status === 419) {
          // CSRF token error
          setError('Session expired. Please refresh the page and try again.');
        } else {
          setError('Login failed. Please try again.');
        }
      } else if (err.request) {
        // Network error
        setError('Network error. Please check your connection and try again.');
      } else {
        // Other errors
        setError('Login failed. Please check your credentials.');
      }
      
      // Don't navigate or call login() on error
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="bg-gray-100 flex items-center justify-center min-h-screen">
      <div className="bg-white rounded-lg shadow-lg w-full max-w-4xl flex">
        <div className="p-6 w-1/2">
          <div className="flex items-center mb-4">
            <div className="w-4 h-4 bg-green-500 mr-2"></div>
            <h2 className="text-xl font-bold">Login</h2>
          </div>
          <p className="text-gray-500 text-sm mb-4">Your Partner in Freshness and Prosperity.</p>
          <form onSubmit={handleSubmit}>
            <div className="mb-4">
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="email">Email*</label>
              <input
                className="w-full p-2 border rounded-lg"
                type="email"
                id="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="Enter your email"
                required
                disabled={isLoading}
              />
            </div>
            <div className="mb-4">
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="password">Password*</label>
              <input
                className="w-full p-2 border rounded-lg"
                type="password"
                id="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="minimum 8 characters"
                required
                disabled={isLoading}
              />
            </div>
            <div className="flex items-center justify-between mb-4">
              <div className="flex items-center">
                <input type="checkbox" id="remember" className="mr-2" disabled={isLoading} />
                <label className="text-sm text-gray-700" htmlFor="remember">Remember me</label>
              </div>
              <a href="/forgot-password" className="text-sm text-green-500">Forgot password?</a>
            </div>
            <button 
              type="submit" 
              className={`w-full p-2 rounded-lg flex items-center justify-center ${
                isLoading 
                  ? 'bg-gray-400 cursor-not-allowed' 
                  : 'bg-green-500 hover:bg-green-600'
              } text-white transition-colors duration-200`}
              disabled={isLoading}
            >
              {isLoading ? (
                <>
                  <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Logging in...
                </>
              ) : (
                'Login'
              )}
            </button>
            {error && <p className="text-red-500 text-sm mt-2">{error}</p>}
          </form>
        </div>
        <div className="w-1/2 p-6">
          <img src="/images/login.jpg" alt="Login Image" className="w-full h-auto object-cover" />
        </div>
      </div>
    </div>
  );
};

export default Login;