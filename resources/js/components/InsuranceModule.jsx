import React, { useState } from "react";

export default function InsuranceModule() {
  const [form, setForm] = useState({
    name: "",
    idNumber: "",
    phone: "",
    startDate: "",
    site: "",
    insuranceCopy: null,
    coverExpiry: "",
  });
  const [sent, setSent] = useState(false);

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
        onSubmit={e => {
          e.preventDefault();
          setSent(true);
        }}
      >
        <div className="text-xl font-semibold text-center mb-4 border-b pb-2">
          Employee Insurance
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Name:</label>
          <input
            type="text"
            className="w-full border rounded px-3 py-2"
            value={form.name}
            onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">ID Number:</label>
          <input
            type="text"
            className="w-full border rounded px-3 py-2"
            value={form.idNumber}
            onChange={e => setForm(f => ({ ...f, idNumber: e.target.value }))}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Phone No:</label>
          <input
            type="text"
            className="w-full border rounded px-3 py-2"
            value={form.phone}
            onChange={e => setForm(f => ({ ...f, phone: e.target.value }))}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Start Date:</label>
          <input
            type="date"
            className="w-full border rounded px-3 py-2"
            value={form.startDate}
            onChange={e => setForm(f => ({ ...f, startDate: e.target.value }))}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Site:</label>
          <input
            type="text"
            className="w-full border rounded px-3 py-2"
            value={form.site}
            onChange={e => setForm(f => ({ ...f, site: e.target.value }))}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block font-medium mb-1">Insurance copy:</label>
          <input
            type="file"
            className="w-full border rounded px-3 py-2"
            onChange={e => setForm(f => ({ ...f, insuranceCopy: e.target.files[0] }))}
            required
          />
        </div>
        <div className="mb-6">
          <label className="block font-medium mb-1">Cover Expiry:</label>
          <input
            type="date"
            className="w-full border rounded px-3 py-2"
            value={form.coverExpiry}
            onChange={e => setForm(f => ({ ...f, coverExpiry: e.target.value }))}
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
      </form>
    </div>
  );
}
