import React, { useState } from "react";
import { useNavigate, useParams, useLocation } from "react-router-dom";

export default function ResetPassword() {
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const { token } = useParams();
  const params = new URLSearchParams(useLocation().search);
  const email = params.get("email");

  console.log("ResetPassword loaded", { token, email });

  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
  }

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError("");
    setMessage("");
    try {
      const response = await fetch("http://localhost:8000/api/reset-password", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token, email, password, password_confirmation: passwordConfirmation }),
      });
      const data = await response.json();
      if (response.ok) {
        setMessage("Password reset successful! You can now log in.");
        setTimeout(() => navigate("/login"), 2000);
      } else {
        setError(data.message || "Error resetting password.");
      }
    } catch (err) {
      setError("Network error. Please try again.");
    } finally {
      setLoading(false);
    }
  };

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