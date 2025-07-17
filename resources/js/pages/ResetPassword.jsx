import React, { useState } from "react";

export default function ResetPasswordForm() {
  const [password, setPassword] = useState("");
  const [password_confirmation, setPasswordConfirmation] = useState("");
  const [message, setMessage] = useState("");
  const params = new URLSearchParams(window.location.search);
  const token = params.get("token");
  const email = params.get("email");

  const handleSubmit = async (e) => {
    e.preventDefault();
    const response = await fetch("/api/reset-password", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ token, email, password, password_confirmation }),
    });
    const data = await response.json();
    if (response.ok) {
      setMessage("Password reset successful! You can now log in.");
    } else {
      setMessage(data.message || "Error resetting password.");
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>Reset Password</h2>
      <input type="hidden" value={token} />
      <input type="hidden" value={email} />
      <div>
        <label>New Password</label>
        <input
          type="password"
          value={password}
          onChange={e => setPassword(e.target.value)}
          required
        />
      </div>
      <div>
        <label>Confirm Password</label>
        <input
          type="password"
          value={password_confirmation}
          onChange={e => setPasswordConfirmation(e.target.value)}
          required
        />
      </div>
      <button type="submit">Reset Password</button>
      {message && <p>{message}</p>}
    </form>
  );
}
