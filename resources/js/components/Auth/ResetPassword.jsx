import React, { useState } from "react";
import { useNavigate, useParams, useLocation } from "react-router-dom";

export default function ResetPassword() {
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  // Get token from URL params (path parameter)
  const { token } = useParams();
  // Get email from query parameters
  const params = new URLSearchParams(useLocation().search);
  const email = params.get("email");

  console.log("ResetPassword loaded", { token, email });

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (password !== passwordConfirmation) {
      setError("Passwords do not match.");
      return;
    }

    if (password.length < 8) {
      setError("Password must be at least 8 characters long.");
      return;
    }

    setLoading(true);
    setError("");
    setMessage("");
    
    const requestData = { 
      token, 
      email, 
      password, 
      password_confirmation: passwordConfirmation 
    };
    
    console.log("Sending password reset request:", requestData);
    
    try {
      const response = await fetch("/api/reset-password", {
        method: "POST",
        headers: { 
          "Content-Type": "application/json",
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify(requestData),
      });
      
      console.log("Response status:", response.status);
      console.log("Response headers:", response.headers);
      
      if (response.ok) {
        const data = await response.json();
        console.log("Success response:", data);
        setMessage("Password reset successful! You can now log in.");
        setTimeout(() => navigate("/login"), 2000);
      } else {
        const errorText = await response.text();
        console.log("Error response text:", errorText);
        
        let errorMessage = "Error resetting password.";
        
        try {
          const errorData = JSON.parse(errorText);
          errorMessage = errorData.message || errorMessage;
        } catch (e) {
          // If JSON parsing fails, use the raw text
          errorMessage = errorText || errorMessage;
        }
        
        setError(errorMessage);
      }
    } catch (err) {
      console.error("Password reset error:", err);
      setError("Network error. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  // Show error if missing token or email
  if (!token || !email) {
    return (
      <div style={{ minHeight: "100vh", display: "flex", alignItems: "center", justifyContent: "center", background: "#f5f6fa" }}>
        <div style={{ background: "#fff", padding: "2.5rem 2rem", borderRadius: "12px", boxShadow: "0 2px 16px rgba(0,0,0,0.07)", maxWidth: 400, width: "100%" }}>
          <h2 style={{ color: "#e74c3c", fontWeight: 700, marginBottom: 8 }}>Invalid Reset Link</h2>
          <p style={{ color: "#555", marginBottom: 24 }}>This password reset link is invalid or has expired.</p>
          <a href="/login" style={{ color: "#1abc60", textDecoration: "none", fontWeight: 500 }}>Back to Login</a>
        </div>
      </div>
    );
  }

  return (
    <div style={{ minHeight: "100vh", display: "flex", alignItems: "center", justifyContent: "center", background: "#f5f6fa" }}>
      <div style={{ background: "#fff", padding: "2.5rem 2rem", borderRadius: "12px", boxShadow: "0 2px 16px rgba(0,0,0,0.07)", maxWidth: 400, width: "100%" }}>
        <h2 style={{ color: "#1abc60", fontWeight: 700, marginBottom: 8 }}>Reset Password</h2>
        <p style={{ color: "#555", marginBottom: 24 }}>Enter your new password below.</p>
        <form onSubmit={handleSubmit}>
          <div style={{ marginBottom: 18 }}>
            <label style={{ display: "block", marginBottom: 6, fontWeight: 500 }}>New Password*</label>
            <input
              type="password"
              value={password}
              onChange={e => setPassword(e.target.value)}
              required
              style={{ width: "100%", padding: "10px 12px", border: "1px solid #ddd", borderRadius: 6, fontSize: 16 }}
              autoComplete="new-password"
            />
          </div>
          <div style={{ marginBottom: 18 }}>
            <label style={{ display: "block", marginBottom: 6, fontWeight: 500 }}>Confirm Password*</label>
            <input
              type="password"
              value={passwordConfirmation}
              onChange={e => setPasswordConfirmation(e.target.value)}
              required
              style={{ width: "100%", padding: "10px 12px", border: "1px solid #ddd", borderRadius: 6, fontSize: 16 }}
              autoComplete="new-password"
            />
          </div>
          {error && <div style={{ color: "#e74c3c", marginBottom: 12 }}>{error}</div>}
          {message && <div style={{ color: "#1abc60", marginBottom: 12 }}>{message}</div>}
          <button
            type="submit"
            disabled={loading}
            style={{ width: "100%", background: "#1abc60", color: "#fff", fontWeight: 600, fontSize: 17, padding: "12px 0", border: "none", borderRadius: 6, cursor: loading ? "not-allowed" : "pointer", marginBottom: 10 }}
          >
            {loading ? "Resetting..." : "Reset Password"}
          </button>
        </form>
        <div style={{ textAlign: "center", marginTop: 10 }}>
          <a href="/login" style={{ color: "#1abc60", textDecoration: "none", fontWeight: 500 }}>Back to Login</a>
        </div>
      </div>
    </div>
  );
} 