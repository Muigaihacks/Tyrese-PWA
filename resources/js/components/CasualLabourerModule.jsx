import React, { useState, useEffect } from 'react';
import axios from 'axios';

export default function CasualLabourerModule() {
  const [profile, setProfile] = useState(null);
  const [todayAttendance, setTodayAttendance] = useState(null);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [showAttendanceHistory, setShowAttendanceHistory] = useState(false);
  const [attendanceHistory, setAttendanceHistory] = useState([]);

  const [timeInData, setTimeInData] = useState({
    job_description: '',
    notes: '',
  });

  const [timeOutData, setTimeOutData] = useState({
    notes: '',
  });

  useEffect(() => {
    loadProfile();
  }, []);

  const loadProfile = async () => {
    try {
      console.log('Loading profile...');
      const response = await axios.get('/api/casual-labourer/profile', {
        withCredentials: true,
      });
      console.log('Profile response:', response.data);
      setProfile(response.data.labourer);
      setTodayAttendance(response.data.today_attendance);
      
    } catch (err) {
      console.error('Profile load error:', err);
      console.error('Error response:', err.response);
      setError('Failed to load profile. Please contact admin.');
    } finally {
      setLoading(false);
    }
  };

  const handleTimeIn = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setMessage('');

    try {
      const response = await axios.post('/api/casual-labourer/time-in', timeInData, {
        withCredentials: true,
      });
      
      setMessage('Successfully clocked in!');
      setTodayAttendance({
        time_in: response.data.time_in,
        job_description: response.data.job_description,
        work_date: new Date().toISOString().split('T')[0],
      });
      setTimeInData({ job_description: '', notes: '' });
      
      setTimeout(() => setMessage(''), 3000);
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to clock in');
    } finally {
      setLoading(false);
    }
  };

  const handleTimeOut = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setMessage('');

    try {
      const response = await axios.post('/api/casual-labourer/time-out', timeOutData, {
        withCredentials: true,
      });
      
      setMessage(`Successfully clocked out!`);
      setTodayAttendance(prev => ({
        ...prev,
        time_out: response.data.time_out,
      }));
      setTimeOutData({ notes: '' });
      
      setTimeout(() => setMessage(''), 3000);
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to clock out');
    } finally {
      setLoading(false);
    }
  };

  const loadAttendanceHistory = async () => {
    try {
      const response = await axios.get('/api/casual-labourer/attendance-history', {
        withCredentials: true,
      });
      setAttendanceHistory(response.data.attendance);
    } catch (err) {
      setError('Failed to load attendance history');
    }
  };

  const formatTime = (timeString) => {
    if (!timeString) return '-';
    try {
      // If it's a full datetime string, extract just the time
      if (timeString.includes('T')) {
        return new Date(timeString).toLocaleTimeString();
      }
      // If it's just a time string, format it
      return timeString;
    } catch (error) {
      console.error('Error formatting time:', timeString, error);
      return timeString || '-';
    }
  };

  const formatDate = (dateString) => {
    if (!dateString) return '-';
    try {
      return new Date(dateString).toLocaleDateString();
    } catch (error) {
      console.error('Error formatting date:', dateString, error);
      return dateString || '-';
    }
  };

  if (loading && !profile) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="text-lg">Loading...</div>
      </div>
    );
  }

  if (!profile) {
    return (
      <div className="max-w-4xl mx-auto p-6">
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          <strong>Profile Not Found:</strong> Please contact the administrator to set up your casual labourer profile.
        </div>
      </div>
    );
  }

  const isClockedIn = todayAttendance && todayAttendance.time_in && !todayAttendance.time_out;
  const isClockedOut = todayAttendance && todayAttendance.time_in && todayAttendance.time_out;

  return (
    <div className="max-w-4xl mx-auto p-6">
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800 mb-2">Casual Labourer Dashboard</h1>
        <p className="text-gray-600">Welcome, {profile.name}!</p>
      </div>

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

      {/* Status Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div className="bg-white p-4 rounded-lg shadow border">
          <h3 className="font-semibold text-gray-700 mb-2">Today's Status</h3>
          <div className="text-sm">
            {!todayAttendance && <span className="text-gray-500">Not clocked in</span>}
            {isClockedIn && <span className="text-green-600">Currently working</span>}
            {isClockedOut && <span className="text-blue-600">Completed for today</span>}
          </div>
        </div>

        <div className="bg-white p-4 rounded-lg shadow border">
          <h3 className="font-semibold text-gray-700 mb-2">Compliance Status</h3>
          <div className="text-sm">
            {profile.is_fully_compliant ? (
              <span className="text-green-600">✓ Fully compliant</span>
            ) : (
              <span className="text-red-600">⚠ Needs attention</span>
            )}
          </div>
        </div>

        <div className="bg-white p-4 rounded-lg shadow border">
          <h3 className="font-semibold text-gray-700 mb-2">Account Status</h3>
          <div className="text-sm">
            <span className={`${profile.status === 'active' ? 'text-green-600' : 'text-red-600'}`}>
              {profile.status === 'active' ? '✓ Active' : '✗ Inactive'}
            </span>
          </div>
        </div>
      </div>

      {/* Time In/Out Section */}
      <div className="bg-white rounded-lg shadow border p-6 mb-6">
        <h2 className="text-xl font-semibold mb-4">Time Tracking</h2>
        
        {!todayAttendance && (
          <form onSubmit={handleTimeIn} className="space-y-4">
            <div>
              <label className="block text-sm font-medium mb-1">Job Description (Optional)</label>
              <input
                type="text"
                value={timeInData.job_description}
                onChange={(e) => setTimeInData({...timeInData, job_description: e.target.value})}
                placeholder="e.g., Base Levelling, Assembly"
                className="w-full border rounded px-3 py-2"
              />
            </div>
            <div>
              <label className="block text-sm font-medium mb-1">Notes (Optional)</label>
              <textarea
                value={timeInData.notes}
                onChange={(e) => setTimeInData({...timeInData, notes: e.target.value})}
                placeholder="Any additional notes..."
                className="w-full border rounded px-3 py-2"
                rows="2"
              />
            </div>
            <button
              type="submit"
              disabled={loading}
              className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:bg-gray-400"
            >
              {loading ? 'Clocking In...' : 'Clock In'}
            </button>
          </form>
        )}

        {isClockedIn && (
          <div className="space-y-4">
            <div className="bg-blue-50 p-4 rounded border">
              <p className="text-blue-800">
                <strong>Clocked in at:</strong> {formatTime(todayAttendance.time_in)}
                {todayAttendance.job_description && (
                  <span className="block mt-1">
                    <strong>Job:</strong> {todayAttendance.job_description}
                  </span>
                )}
              </p>
            </div>
            
            <form onSubmit={handleTimeOut} className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-1">End of Day Notes (Optional)</label>
                <textarea
                  value={timeOutData.notes}
                  onChange={(e) => setTimeOutData({...timeOutData, notes: e.target.value})}
                  placeholder="Any notes about today's work..."
                  className="w-full border rounded px-3 py-2"
                  rows="2"
                />
              </div>
              <button
                type="submit"
                disabled={loading}
                className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 disabled:bg-gray-400"
              >
                {loading ? 'Clocking Out...' : 'Clock Out'}
              </button>
            </form>
          </div>
        )}

        {isClockedOut && (
          <div className="bg-green-50 p-4 rounded border">
            <p className="text-green-800">
              <strong>Completed for today</strong><br />
              <strong>Time in:</strong> {formatTime(todayAttendance.time_in)}<br />
              <strong>Time out:</strong> {formatTime(todayAttendance.time_out)}<br />
            </p>
          </div>
        )}
      </div>

      {/* Profile Section */}
      <div className="bg-white rounded-lg shadow border p-6 mb-6">
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-semibold">Profile & Compliance</h2>
        </div>

        <div className="space-y-3">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <strong>Next of Kin:</strong> {profile.next_of_kin_name}
            </div>
            <div>
              <strong>Phone:</strong> {profile.next_of_kin_phone}
            </div>
          </div>
          
          <div className="border-t pt-3">
            <strong>Compliance Status:</strong>
            <div className="mt-2 space-y-1 text-sm">
              {[
                { key: 'health_declaration', label: 'Health Declaration' },
                { key: 'skills_confirmation', label: 'Skills Confirmation' },
                { key: 'ppe_provided', label: 'PPE Provided' },
                { key: 'safety_briefing', label: 'Safety Briefing' },
                { key: 'tool_safety_agreement', label: 'Tool Safety Agreement' },
                { key: 'accident_cover_enrolled', label: 'Accident Cover Enrolled' },
                { key: 'data_consent', label: 'Data Consent' },
              ].map((item) => (
                <div key={item.key} className="flex items-center space-x-2">
                  <span className={profile[item.key] ? 'text-green-600' : 'text-red-600'}>
                    {profile[item.key] ? '✓' : '✗'}
                  </span>
                  <span>{item.label}</span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Attendance History */}
      <div className="bg-white rounded-lg shadow border p-6">
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-semibold">Attendance History</h2>
          <button
            onClick={() => {
              if (!showAttendanceHistory) {
                loadAttendanceHistory();
              }
              setShowAttendanceHistory(!showAttendanceHistory);
            }}
            className="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700"
          >
            {showAttendanceHistory ? 'Hide History' : 'Show History'}
          </button>
        </div>

        {showAttendanceHistory && (
          <div className="overflow-x-auto">
            <table className="min-w-full table-auto">
              <thead>
                <tr className="bg-gray-50">
                  <th className="px-4 py-2 text-left">Date</th>
                  <th className="px-4 py-2 text-left">Time In</th>
                  <th className="px-4 py-2 text-left">Time Out</th>
                  <th className="px-4 py-2 text-left">Job</th>
                </tr>
              </thead>
              <tbody>
                {attendanceHistory.map((record) => (
                  <tr key={record.id} className="border-b">
                    <td className="px-4 py-2">{formatDate(record.work_date)}</td>
                    <td className="px-4 py-2">
                      {record.time_in ? formatTime(record.time_in) : '-'}
                    </td>
                    <td className="px-4 py-2">
                      {record.time_out ? formatTime(record.time_out) : '-'}
                    </td>
                    <td className="px-4 py-2">{record.job_description || '-'}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
} 