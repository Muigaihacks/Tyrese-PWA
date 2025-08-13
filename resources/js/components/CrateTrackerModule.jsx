import React, { useState, useEffect } from "react";
import axios from "axios";

export default function CrateTrackerModule() {
  const [hubs, setHubs] = useState([]);
  const [movements, setMovements] = useState([]);
  const [loading, setLoading] = useState(true);
  const [success, setSuccess] = useState(null);
  const [error, setError] = useState(null);
  const [form, setForm] = useState({
    from_hub_id: "",
    to_hub_id: "",
    crate_count: "",
    scale_type: "",
    scale_count: "",
    notes: "",
  });



  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    setLoading(true);
    try {
      const [hubsRes, movementsRes] = await Promise.all([
        axios.get("/api/crate-tracker/hubs"),
        axios.get("/api/crate-tracker/movements"),
      ]);

      setHubs(hubsRes.data);
      setMovements(movementsRes.data);
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
        scale_count: "",
        notes: "",
      });
      
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    } catch (err) {
      setError(err.response?.data?.error || "Failed to record movement.");
    }
  };

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const getScaleTypeLabel = (type) => {
    const types = {
      platform_scale: "Platform Scale",
      field_scale: "Field Scale",
      kitchen_scale: "Kitchen Scale",
      crane_scale: "Crane Scale",
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

      <div className="max-w-2xl mx-auto">
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
                  <option value="platform_scale">Platform Scale</option>
                  <option value="field_scale">Field Scale</option>
                  <option value="kitchen_scale">Kitchen Scale</option>
                  <option value="crane_scale">Crane Scale</option>
                </select>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Number of Scales
                </label>
                <input
                  type="number"
                  name="scale_count"
                  value={form.scale_count}
                  onChange={handleChange}
                  min="0"
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Number of scales being moved"
                />
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
                   <th className="text-left py-2">Scale Count</th>
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
                     <td className="py-2 text-sm">{movement.scale_count || "-"}</td>
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
