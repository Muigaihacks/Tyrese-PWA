'use client';

import { useState, Suspense } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import { Eye, EyeOff, Lock, CheckCircle, AlertCircle } from 'lucide-react';

function ResetPasswordForm() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const token = searchParams.get('token');
  const email = searchParams.get('email');

  const [formData, setFormData] = useState({
    password: '',
    password_confirmation: '',
  });
  const [showPassword, setShowPassword] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [status, setStatus] = useState<'idle' | 'success' | 'error'>('idle');
  const [message, setMessage] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (formData.password !== formData.password_confirmation) {
        setStatus('error');
        setMessage('Passwords do not match');
        return;
    }

    setIsSubmitting(true);
    setStatus('idle');

    try {
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/reset-password`, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            token,
            email,
            password: formData.password,
            password_confirmation: formData.password_confirmation
        })
      });

      const data = await res.json();

      if (res.ok) {
        setStatus('success');
        setMessage(data.message || 'Password has been reset successfully.');
        setTimeout(() => {
            router.push('/');
        }, 3000);
      } else {
        setStatus('error');
        setMessage(data.message || data.email || 'Failed to reset password.');
      }
    } catch (error) {
      setStatus('error');
      setMessage('An error occurred. Please try again.');
    } finally {
      setIsSubmitting(false);
    }
  };

  if (!token || !email) {
      return (
          <div className="text-center p-8 bg-white rounded-lg shadow">
              <AlertCircle className="w-12 h-12 text-red-500 mx-auto mb-4" />
              <h2 className="text-xl font-bold text-gray-900 mb-2">Invalid Link</h2>
              <p className="text-gray-600 mb-4">This password reset link is invalid or incomplete.</p>
              <button onClick={() => router.push('/')} className="text-primary-600 hover:underline">Return Home</button>
          </div>
      );
  }

  if (status === 'success') {
      return (
          <div className="text-center p-8 bg-white rounded-lg shadow">
              <CheckCircle className="w-12 h-12 text-green-500 mx-auto mb-4" />
              <h2 className="text-xl font-bold text-gray-900 mb-2">Password Reset!</h2>
              <p className="text-gray-600 mb-4">{message}</p>
              <p className="text-sm text-gray-500">Redirecting to login...</p>
          </div>
      );
  }

  return (
    <form onSubmit={handleSubmit} className="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <div className="text-center mb-8">
        <h1 className="text-2xl font-bold text-gray-900">Set New Password</h1>
        <p className="text-gray-600 mt-2 text-sm">Please create a strong password for your account.</p>
      </div>

      {status === 'error' && (
        <div className="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm flex items-center gap-2">
            <AlertCircle className="w-4 h-4" />
            {message}
        </div>
      )}

      <div className="space-y-4">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">New Password</label>
          <div className="relative">
            <input
              type={showPassword ? 'text' : 'password'}
              value={formData.password}
              onChange={(e) => setFormData({ ...formData, password: e.target.value })}
              className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent pr-10 !text-gray-900"
              style={{ color: '#111827' }}
              placeholder="Min. 8 characters"
              required
              minLength={8}
            />
            <button
              type="button"
              onClick={() => setShowPassword(!showPassword)}
              className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
              {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
            </button>
          </div>
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <div className="relative">
            <input
              type={showPassword ? 'text' : 'password'}
              value={formData.password_confirmation}
              onChange={(e) => setFormData({ ...formData, password_confirmation: e.target.value })}
              className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent pr-10 !text-gray-900"
              style={{ color: '#111827' }}
              placeholder="Re-enter password"
              required
              minLength={8}
            />
            <Lock className="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          </div>
        </div>

        <button
          type="submit"
          disabled={isSubmitting}
          className="w-full bg-primary-600 text-white py-2.5 rounded-lg hover:bg-primary-700 font-medium transition-colors disabled:opacity-50 mt-6"
        >
          {isSubmitting ? 'Resetting...' : 'Reset Password'}
        </button>
      </div>
    </form>
  );
}

export default function ResetPasswordPage() {
    return (
        <div className="min-h-screen bg-gray-50 flex items-center justify-center px-4">
            <Suspense fallback={<div>Loading...</div>}>
                <ResetPasswordForm />
            </Suspense>
        </div>
    );
}

