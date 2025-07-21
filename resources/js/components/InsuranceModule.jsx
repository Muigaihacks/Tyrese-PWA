import React, { useState } from "react";
import axios from "axios";

export default function InsuranceModule() {
  const [form, setForm] = useState({
    name: "",
    id_number: "",
    phone_number: "",
    start_date: "",
    site: "",
    insurance_copy: null,
    cover_expiry: "",
  });
  const [sent, setSent] = useState(false);
  const [error, setError] = useState('');

  const handleChange = (e) => {
    const { name, value, files } = e.target;
    setForm({ ...form, [name]: files ? files[0] : value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSent(false);
    setError('');

    const formData = new FormData();
    for (const key in form) {
      formData.append(key, form[key]);
    }

    try {
      await axios.post('/api/insurances', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      setSent(true);
      setForm({
        name: "",
        id_number: "",
        phone_number: "",
        start_date: "",
        site: "",
        insurance_copy: null,
        cover_expiry: "",
      });
    } catch (error) {
      setError('Failed to submit the form. Please check your input.');
    }
  };

  return (
    <div className="p-6 mt-6">
      {/* Header */}
      <div className="flex items-center mb-4">
        <div className="w-8 h-8 bg-yellow-400 rounded mr-4" />
        <h2 className="text-2xl font-bold text-blue-700">Insurance Module</h2>
      </div>
      {/* Tagline */}
      <div className="mb-8 text-lg">
        At{" "}
        <span className="font-bold text-yellow-500">SokoFresh</span>{" "}
        the safety of our team always comes first!
      </div>
      {/* Form */}
      <form
        className="border rounded p-6 bg-white shadow-md max-w-xl"
        onSubmit={handleSubmit}
      >
        <div className="text-xl font-semibold text-center mb-4 border-b pb-2">
          Employee Insurance
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Name:</label>
          <input
            type="text"
            name="name"
            className="w-full border rounded px-3 py-2"
            value={form.name}
            onChange={handleChange}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">ID Number:</label>
          <input
            type="text"
            name="id_number"
            className="w-full border rounded px-3 py-2"
            value={form.id_number}
            onChange={handleChange}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Phone No:</label>
          <input
            type="text"
            name="phone_number"
            className="w-full border rounded px-3 py-2"
            value={form.phone_number}
            onChange={handleChange}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Start Date:</label>
          <input
            type="date"
            name="start_date"
            className="w-full border rounded px-3 py-2"
            value={form.start_date}
            onChange={handleChange}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Site:</label>
          <input
            type="text"
            name="site"
            className="w-full border rounded px-3 py-2"
            value={form.site}
            onChange={handleChange}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Insurance copy:</label>
          <input
            type="file"
            name="insurance_copy"
            className="w-full border rounded px-3 py-2"
            onChange={handleChange}
            required
          />
        </div>
        <div className="mb-6">
          <label className="block font-medium mb-1">Cover Expiry:</label>
          <input
            type="date"
            name="cover_expiry"
            className="w-full border rounded px-3 py-2"
            value={form.cover_expiry}
            onChange={handleChange}
            required
          />
        </div>
        <button
          type="submit"
          className="w-full text-green-600 border border-green-600 py-2 rounded font-semibold hover:bg-green-50 transition"
        >
          Submit
        </button>
        {sent && (
          <div className="mt-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded">
            Insurance details submitted successfully!
          </div>
        )}
        {error && (
          <div className="mt-4 p-3 bg-red-100 border border-red-400 text-red-800 rounded">
            {error}
          </div>
        )}
      </form>
    </div>
  );
}
