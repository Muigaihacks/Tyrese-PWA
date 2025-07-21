import React, { useState } from "react";
import axios from "axios";

export default function StorageModule() {
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState('');
  const [form, setForm] = useState({
    client_name: '',
    phone_number: '',
    email: '',
    product_name: '',
    quantity: '',
    unit: 'kg',
    date: '',
    fee: '',
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm({ ...form, [name]: value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSuccess(false);
    setError('');

    const dataToSubmit = {
      ...form,
      quantity: Number(form.quantity), // ensure numeric
    };

    try {
      await axios.post('/api/storage', dataToSubmit);
      setSuccess(true);
      setForm({
        client_name: '',
        phone_number: '',
        email: '',
        product_name: '',
        quantity: '',
        unit: 'kg',
        date: '',
        fee: '',
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
        <h2 className="text-2xl font-bold text-gray-800">Storage Module</h2>
      </div>
      
      {/* Welcome Message */}
      <div className="mb-6">
        <span className="text-green-600 font-semibold">Welcome</span>
        <span className="text-gray-700">
          {" "}to our Cold Storage Facility! Kindly fill the form to submit a new entry.
        </span>
      </div>
      
      {/* Logo */}
      <div className="mb-2">
        <span className="text-4xl font-extrabold text-green-900 border-4 border-green-900 px-4 py-1 inline-block">SokoFresh</span>
        <span className="text-2xl text-green-900 align-super ml-1">*</span>
      </div>
      
      {/* Tagline */}
      <div className="text-green-600 font-semibold text-lg mb-6">
        Maintaining Produce Freshness Through Solar Powered Cooling
      </div>
      
      {success && (
        <div className="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded">
          Submission has been successfully recorded!
        </div>
      )}
      {error && (
        <div className="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded">
          {error}
        </div>
      )}

      {/* Form */}
      <form
        className="border rounded p-6 bg-white shadow-md max-w-xl"
        onSubmit={handleSubmit}
      >
        <div className="text-xl font-semibold text-center mb-4 border-b pb-2">New Storage Entry</div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Client Name:</label>
          <input type="text" name="client_name" value={form.client_name} onChange={handleChange} className="w-full border rounded px-3 py-2" required />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Phone No:</label>
          <input type="text" name="phone_number" value={form.phone_number} onChange={handleChange} className="w-full border rounded px-3 py-2" required />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Email:</label>
          <input type="email" name="email" value={form.email} onChange={handleChange} className="w-full border rounded px-3 py-2" />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Product Name:</label>
          <input type="text" name="product_name" value={form.product_name} onChange={handleChange} className="w-full border rounded px-3 py-2" required />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Quantity:</label>
          <div className="flex">
            <input
              type="number"
              name="quantity"
              value={form.quantity}
              onChange={handleChange}
              className="w-full border rounded-l px-3 py-2"
              min="0"
              required
            />
            <select name="unit" value={form.unit} onChange={handleChange} className="border border-l-0 rounded-r px-2 py-2 bg-gray-100 text-gray-700">
              <option value="kg">Kg</option>
              <option value="tonne">Tonne</option>
            </select>
          </div>
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Check-in Date:</label>
          <input type="date" name="date" value={form.date} onChange={handleChange} className="w-full border rounded px-3 py-2" required />
        </div>
        <div className="mb-6">
          <label className="block font-medium mb-1">Fee Charged:</label>
          <div className="flex items-center">
            <span className="inline-block bg-gray-100 border border-gray-300 rounded-l px-3 py-2 text-gray-700">Ksh</span>
            <input
              type="number"
              name="fee"
              value={form.fee}
              onChange={handleChange}
              className="w-full border-t border-b border-r rounded-r px-3 py-2"
              style={{ borderLeft: "none" }}
              min="0"
              required
            />
          </div>
        </div>
        <button
          type="submit"
          className="w-full bg-green-600 text-white py-2 rounded font-semibold hover:bg-green-700 transition"
        >
          Submit
        </button>
      </form>
    </div>
  );
}
