import React from "react";

export default function StorageModule() {
  return (
    <div>
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
      <div className="text-green-600 font-semibold text-lg mb-4">
        Maintaining Produce Freshness Through Solar Powered Cooling
      </div>
      {/* Main Image */}
      <div className="mb-8">
        <img
          src="/images/storage.jpg"
          alt="Cold Storage"
          className="w-full max-w-xl rounded shadow"
        />
      </div>
      {/* Form */}
      <form className="border rounded p-6 bg-white shadow-md max-w-xl mx-auto">
        <div className="text-xl font-semibold text-center mb-4 border-b pb-2">New Storage Entry</div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Client Name:</label>
          <input type="text" className="w-full border rounded px-3 py-2" />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Phone No:</label>
          <input type="text" className="w-full border rounded px-3 py-2" />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Email:</label>
          <input type="email" className="w-full border rounded px-3 py-2" />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Product Name:</label>
          <input type="text" className="w-full border rounded px-3 py-2" />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Quantity:</label>
          <input type="number" className="w-full border rounded px-3 py-2" />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Check-in Date:</label>
          <input type="date" className="w-full border rounded px-3 py-2" />
        </div>
        <div className="mb-6">
          <label className="block font-medium mb-1">Fee Charged:</label>
          <input type="text" className="w-full border rounded px-3 py-2" />
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
