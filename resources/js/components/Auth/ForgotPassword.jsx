import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

const ForgotPassword = () => {
  const [email, setEmail] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setMessage('');

    console.log('Submitting forgot password request for:', email);
    console.log('Current URL:', window.location.href);
    console.log('Axios default base URL:', axios.defaults.baseURL);

    try {
      const response = await axios.post('/api/forgot-password', { email });
      console.log('Response:', response);
      setMessage('Password reset link has been sent to your email address.');
    } catch (err) {
      console.error('Error details:', err);
      console.error('Response data:', err.response?.data);
      console.error('Response status:', err.response?.status);
      console.error('Request URL:', err.config?.url);
      console.error('Request method:', err.config?.method);
      console.error('Request headers:', err.config?.headers);
      setError('Failed to send reset email. Please check your email address.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-gray-100 flex items-center justify-center min-h-screen">
      <div className="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div className="flex items-center mb-4">
          <div className="w-4 h-4 bg-green-500 mr-2"></div>
          <h2 className="text-xl font-bold">Forgot Password</h2>
        </div>
        <p className="text-gray-500 text-sm mb-4">Enter your email address and we'll send you a link to reset your password.</p>
        
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
            />
          </div>
          
          <button 
            type="submit" 
            disabled={loading}
            className="w-full bg-green-500 text-white p-2 rounded-lg disabled:bg-gray-400"
          >
            {loading ? 'Sending...' : 'Send Reset Link'}
          </button>
          
          {error && <p className="text-red-500 text-sm mt-2">{error}</p>}
          {message && <p className="text-green-500 text-sm mt-2">{message}</p>}
        </form>
        
        <div className="text-center mt-4">
          <a href="/login" className="text-sm text-green-500">Back to Login</a>
        </div>
      </div>
    </div>
  );
};

export default ForgotPassword; 