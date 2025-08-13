import React, { useState, useEffect } from "react";
import axios from "axios";

export default function CrateTrackerModule() {
  const [hubs, setHubs] = useState([]);
  const [coldStorageUnits, setColdStorageUnits] = useState([]);
  const [movements, setMovements] = useState([]);
  const [visits, setVisits] = useState([]);
  const [loading, setLoading] = useState(true);
  const [success, setSuccess] = useState(null);
  const [error, setError] = useState(null);
  const [form, setForm] = useState({
    from_hub_id: "",
    to_hub_id: "",
    crate_count: "",
    scale_type: "",
    notes: "",
    visit_id: "",
  });

  const [coldStorageForm, setColdStorageForm] = useState({
    unit_id: "",
    crate_count: "",
    description: "",
  });

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    setLoading(true);
    try {
      const [hubsRes, unitsRes, movementsRes, visitsRes] = await Promise.all([
        axios.get("/api/crate-tracker/hubs"),
        axios.get("/api/crate-tracker/cold-storage-units"),
        axios.get("/api/crate-tracker/movements"),
        axios.get("/api/visits/dropdown"),
      ]);

      setHubs(hubsRes.data);
      setColdStorageUnits(unitsRes.data);
      setMovements(movementsRes.data);
      setVisits(visitsRes.data);
    } catch (err) {
      setError("Failed to load data.");
    } finally {
      setLoading(false);
    }
  };

  const handleMovementSubmit = async (e) => {
    e.preventDefault();
    setSuccess(null);
    setError(null);

    if (!form.from_hub_id || !form.to_hub_id || !form.crate_count) {
      setError("Please fill in all required fields.");
      return;
    }

    if (form.from_hub_id === form.to_hub_id) {
      setError("Source and destination hubs cannot be the same.");
      return;
    }

    try {
      await axios.post("/api/crate-tracker/movement", form);
      setSuccess("Movement recorded successfully! Page will reload in 2 seconds...");
      setForm({
        from_hub_id: "",
        to_hub_id: "",
        crate_count: "",
        scale_type: "",
        notes: "",
        visit_id: "",
      });
      
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    } catch (err) {
      setError(err.response?.data?.error || "Failed to record movement.");
    }
  };

  const handleColdStorageSubmit = async (e) => {
    e.preventDefault();
    setSuccess(null);
    setError(null);

    if (!coldStorageForm.unit_id || !coldStorageForm.crate_count) {
      setError("Please fill in all required fields.");
      return;
    }

    try {
      await axios.post("/api/crate-tracker/cold-storage-unit", coldStorageForm);
      setSuccess("Cold storage unit updated successfully! Page will reload in 2 seconds...");
      setColdStorageForm({
        unit_id: "",
        crate_count: "",
        description: "",
      });
      
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    } catch (err) {
      setError(err.response?.data?.error || "Failed to update cold storage unit.");
    }
  };

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleColdStorageChange = (e) => {
    setColdStorageForm({ ...coldStorageForm, [e.target.name]: e.target.value });
  };

  const getScaleTypeLabel = (type) => {
    const types = {
      digital_scale: "Digital Scale",
      analog_scale: "Analog Scale",
      hanging_scale: "Hanging Scale",
      platform_scale: "Platform Scale",
    };
    return types[type] || type;
  };

  if (loading) {
    return <div className="p-6">Loading...</div>;
  }

  return (
    <div className="p-6">
      <h2 className="text-2xl font-bold mb-6">Crate Tracker</h2>
      
      {error && <div className="text-red-500 mb-4">{error}</div>}
      {success && <div className="text-green-500 mb-4">{success}</div>}

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Movement Form */}
        <div className="bg-white p-6 rounded-lg shadow">
          <h3 className="text-lg font-semibold mb-4">Record Crate Movement</h3>
          <form onSubmit={handleMovementSubmit}>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  From Hub *
                </label>
                <select
                  name="from_hub_id"
                  value={form.from_hub_id}
                  onChange={handleChange}
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  required
                >
                  <option value="">Select source hub</option>
                  {hubs.map((hub) => (
                    <option key={hub.id} value={hub.id}>
                      {hub.name} ({hub.crate_count} crates)
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  To Hub *
                </label>
                <select
                  name="to_hub_id"
                  value={form.to_hub_id}
                  onChange={handleChange}
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  required
                >
                  <option value="">Select destination hub</option>
                  {hubs.map((hub) => (
                    <option key={hub.id} value={hub.id}>
                      {hub.name} ({hub.crate_count} crates)
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Number of Crates *
                </label>
                <input
                  type="number"
                  name="crate_count"
                  value={form.crate_count}
                  onChange={handleChange}
                  min="0"
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  required
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Scale Type (if moving scales)
                </label>
                <select
                  name="scale_type"
                  value={form.scale_type}
                  onChange={handleChange}
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">No scale movement</option>
                  <option value="digital_scale">Digital Scale</option>
                  <option value="analog_scale">Analog Scale</option>
                  <option value="hanging_scale">Hanging Scale</option>
                  <option value="platform_scale">Platform Scale</option>
                </select>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Visit (Optional)
                </label>
                <select
                  name="visit_id"
                  value={form.visit_id}
                  onChange={handleChange}
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">No visit</option>
                  {visits.map((visit) => (
                    <option key={visit.id} value={visit.id}>
                      Visit {visit.id} - {visit.leased_unit?.name}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Notes
                </label>
                <textarea
                  name="notes"
                  value={form.notes}
                  onChange={handleChange}
                  rows="3"
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Additional notes about the movement..."
                />
              </div>

              <button
                type="submit"
                className="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors"
              >
                Record Movement
              </button>
            </div>
          </form>
        </div>

        {/* Cold Storage Units Form (Kibiku Only) */}
        <div className="bg-white p-6 rounded-lg shadow">
          <h3 className="text-lg font-semibold mb-4">Kibiku Cold Storage Units</h3>
          <form onSubmit={handleColdStorageSubmit}>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Unit ID *
                </label>
                <input
                  type="text"
                  name="unit_id"
                  value={coldStorageForm.unit_id}
                  onChange={handleColdStorageChange}
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  placeholder="e.g., CS001, CS002"
                  required
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Crate Count *
                </label>
                <input
                  type="number"
                  name="crate_count"
                  value={coldStorageForm.crate_count}
                  onChange={handleColdStorageChange}
                  min="0"
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  required
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Description
                </label>
                <textarea
                  name="description"
                  value={coldStorageForm.description}
                  onChange={handleColdStorageChange}
                  rows="2"
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Unit description..."
                />
              </div>

              <button
                type="submit"
                className="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors"
              >
                Update Cold Storage Unit
              </button>
            </div>
          </form>

          {/* Cold Storage Units List */}
          <div className="mt-6">
            <h4 className="text-md font-semibold mb-3">Current Cold Storage Units</h4>
            {coldStorageUnits.length > 0 ? (
              <div className="space-y-2">
                {coldStorageUnits.map((unit) => (
                  <div key={unit.id} className="bg-gray-50 p-3 rounded">
                    <div className="font-medium">Unit {unit.unit_id}</div>
                    <div className="text-sm text-gray-600">
                      Crates: {unit.crate_count}
                      {unit.description && ` • ${unit.description}`}
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-gray-500 text-sm">No cold storage units configured yet.</p>
            )}
          </div>
        </div>
      </div>

      {/* Hubs Overview */}
      <div className="mt-8 bg-white p-6 rounded-lg shadow">
        <h3 className="text-lg font-semibold mb-4">Hubs Overview</h3>
        <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
          {hubs.map((hub) => (
            <div key={hub.id} className="bg-gray-50 p-4 rounded text-center">
              <div className="font-semibold text-sm">{hub.name}</div>
              <div className="text-lg font-bold text-blue-600">{hub.crate_count}</div>
              <div className="text-xs text-gray-600">crates</div>
              <div className="text-sm text-gray-600">{hub.scale_count} scales</div>
            </div>
          ))}
        </div>
      </div>

      {/* Recent Movements */}
      <div className="mt-8 bg-white p-6 rounded-lg shadow">
        <h3 className="text-lg font-semibold mb-4">Recent Movements</h3>
        {movements.length > 0 ? (
          <div className="overflow-x-auto">
            <table className="min-w-full">
              <thead>
                <tr className="border-b">
                  <th className="text-left py-2">Date</th>
                  <th className="text-left py-2">From</th>
                  <th className="text-left py-2">To</th>
                  <th className="text-left py-2">Crates</th>
                  <th className="text-left py-2">Scale</th>
                  <th className="text-left py-2">User</th>
                  <th className="text-left py-2">Notes</th>
                </tr>
              </thead>
              <tbody>
                {movements.slice(0, 10).map((movement) => (
                  <tr key={movement.id} className="border-b">
                    <td className="py-2 text-sm">
                      {new Date(movement.created_at).toLocaleDateString()}
                    </td>
                    <td className="py-2 font-medium">{movement.from_hub?.name}</td>
                    <td className="py-2 font-medium">{movement.to_hub?.name}</td>
                    <td className="py-2">{movement.crate_count}</td>
                    <td className="py-2">
                      {movement.scale_type ? (
                        <span className="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                          {getScaleTypeLabel(movement.scale_type)}
                        </span>
                      ) : (
                        <span className="text-gray-400">-</span>
                      )}
                    </td>
                    <td className="py-2 text-sm">{movement.user?.name}</td>
                    <td className="py-2 text-sm text-gray-600 max-w-xs truncate">
                      {movement.notes || "-"}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <p className="text-gray-500">No movements recorded yet.</p>
        )}
      </div>
    </div>
  );
}
