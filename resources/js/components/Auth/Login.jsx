import React, { useState } from 'react';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    try {
      const response = await fetch('http://localhost:8000/api/login', { // Laravel endpoint
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json', // Laravel expects this header
        },
        body: JSON.stringify({ email, password }),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Login failed');
      }

      // On success, redirect or store token
      console.log('Login successful', data);
      // Example: window.location.href = '/dashboard';
    } catch (err) {
      setError(err.message);
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
              />
            </div>
            <div className="flex items-center justify-between mb-4">
              <div className="flex items-center">
                <input type="checkbox" id="remember" className="mr-2" />
                <label className="text-sm text-gray-700" htmlFor="remember">Remember me</label>
              </div>
              <a href="#" className="text-sm text-green-500">Forgot password?</a>
            </div>
            <button type="submit" className="w-full bg-green-500 text-white p-2 rounded-lg">Login</button>
            {error && <p className="text-red-500 text-sm mt-2">{error}</p>}
            <p className="text-center text-sm text-yellow-500 mt-4">Not registered yet? <a href="#" className="text-yellow-700">Create a new account</a></p>
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