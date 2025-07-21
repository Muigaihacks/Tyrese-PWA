import React, { useState, useEffect } from "react";
import axios from "axios";

export default function ReturnForm() {
  const [inventories, setInventories] = useState([]);
  const [locations, setLocations] = useState([]);
  const [visits, setVisits] = useState([]);
  const [form, setForm] = useState({
    inventory_id: "",
    location_id: "",
    visit_id: "",
    quantity: "",
    condition_after: "",
    notes: "",
  });
  const [loading, setLoading] = useState(true);
  const [success, setSuccess] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    setLoading(true);
    Promise.all([
      axios.get("/api/inventories"),
      axios.get("/api/locations/dropdown"),
      axios.get("/api/visits/dropdown"),
    ])
      .then(([invRes, locRes, visitRes]) => {
        setInventories(Array.isArray(invRes.data) ? invRes.data : []);
        setLocations(Array.isArray(locRes.data) ? locRes.data : []);
        setVisits(Array.isArray(visitRes.data) ? visitRes.data : []);
      })
      .catch(() => setError("Failed to load dropdown data."))
      .finally(() => setLoading(false));
  }, []);

  const handleChange = e => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async e => {
    e.preventDefault();
    setSuccess(null);
    setError(null);
    try {
      await axios.post("/api/inventory/return", form);
      setSuccess("Return successful!");
      setForm({
        inventory_id: "",
        location_id: "",
        visit_id: "",
        quantity: "",
        condition_after: "",
        notes: "",
      });
    } catch {
      setError("Return failed. Please check your input.");
    }
  };

  return (
    <form
      onSubmit={handleSubmit}
      className="flex-1 bg-white rounded-lg shadow p-6 min-w-[320px]"
    >
      <h3 className="mb-4 text-lg font-semibold">Return Tools</h3>
      {loading ? (
        <div>Loading...</div>
      ) : (
        <>
          <div className="mb-3">
            <label>Inventory Item:</label>
            <select
              name="inventory_id"
              value={form.inventory_id}
              onChange={handleChange}
              required
              className="block w-full border rounded p-2"
            >
              <option value="">Select item</option>
              {inventories.map(inv => (
                <option key={inv.id} value={inv.id}>
                  {inv.product}
                </option>
              ))}
            </select>
          </div>
          <div className="mb-3">
            <label>Location:</label>
            <select
              name="location_id"
              value={form.location_id}
              onChange={handleChange}
              required
              className="block w-full border rounded p-2"
            >
              <option value="">Select location</option>
              {locations.map(loc => (
                <option key={loc.id} value={loc.id}>
                  {loc.name}
                </option>
              ))}
            </select>
          </div>
          <div className="mb-3">
            <label>Visit:</label>
            <select
              name="visit_id"
              value={form.visit_id}
              onChange={handleChange}
              required
              className="block w-full border rounded p-2"
            >
              <option value="">Select visit</option>
              {visits.map(visit => (
                <option key={visit.id} value={visit.id}>
                  {visit.name}
                </option>
              ))}
            </select>
          </div>
          <div className="mb-3">
            <label>Quantity:</label>
            <input
              type="number"
              name="quantity"
              value={form.quantity}
              onChange={handleChange}
              min="1"
              required
              className="block w-full border rounded p-2"
            />
          </div>
          <div className="mb-3">
            <label>Condition After:</label>
            <input
              type="text"
              name="condition_after"
              value={form.condition_after}
              onChange={handleChange}
              required
              className="block w-full border rounded p-2"
            />
          </div>
          <div className="mb-3">
            <label>Notes:</label>
            <input
              type="text"
              name="notes"
              value={form.notes}
              onChange={handleChange}
              className="block w-full border rounded p-2"
            />
          </div>
          <button
            type="submit"
            className="w-full bg-green-600 text-white py-2 rounded mt-2"
          >
            Return
          </button>
          {success && <div className="text-green-600 mt-2">{success}</div>}
          {error && <div className="text-red-600 mt-2">{error}</div>}
        </>
      )}
    </form>
  );
}