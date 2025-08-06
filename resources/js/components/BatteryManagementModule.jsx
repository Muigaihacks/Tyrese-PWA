import React, { useState, useEffect } from "react";
import axios from "axios";

export default function BatteryManagementModule() {
  const [batteries, setBatteries] = useState([]);
  const [coldStorageUnits, setColdStorageUnits] = useState([]);
  const [selectedBatteries, setSelectedBatteries] = useState([]);
  const [form, setForm] = useState({
    from_unit_id: "",
    to_unit_id: "",
    notes: "",
  });
  const [loading, setLoading] = useState(true);
  const [success, setSuccess] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    setLoading(true);
    Promise.all([
      axios.get("/api/batteries"),
      axios.get("/api/leased-units"),
    ])
      .then(([batteryRes, unitRes]) => {
        setBatteries(Array.isArray(batteryRes.data) ? batteryRes.data : []);
        setColdStorageUnits(Array.isArray(unitRes.data) ? unitRes.data : []);
      })
      .catch(() => setError("Failed to load data."))
      .finally(() => setLoading(false));
  }, []);

  const handleBatteryToggle = (battery) => {
    setSelectedBatteries(prev => {
      const exists = prev.find(b => b.id === battery.id);
      if (exists) {
        return prev.filter(b => b.id !== battery.id);
      } else {
        return [...prev, { 
          ...battery, 
          condition_before: '' 
        }];
      }
    });
  };

  const handleBatteryChange = (batteryId, field, value) => {
    setSelectedBatteries(prev => 
      prev.map(battery => 
        battery.id === batteryId ? { ...battery, [field]: value } : battery
      )
    );
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (selectedBatteries.length === 0) {
      setError("Please select at least one battery.");
      return;
    }

    setSuccess(null);
    setError(null);
    
    try {
      const batteriesData = selectedBatteries.map(battery => ({
        battery_id: battery.id,
        condition_before: battery.condition_before
      }));

      await axios.post("/api/batteries/swap", {
        ...form,
        batteries_data: batteriesData,
        action_type: 'batteries'
      });
      
      setSuccess("Battery swap successful! Page will reload in 2 seconds...");
      setForm({
        from_unit_id: "",
        to_unit_id: "",
        notes: "",
      });
      setSelectedBatteries([]);
      
      // Auto-reload the page after 2 seconds
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    } catch {
      setError("Battery swap failed. Please check your input.");
    }
  };

  return (
    <div className="p-6">
      <div className="flex items-center mb-4">
        <div className="w-8 h-8 bg-green-400 rounded mr-4" />
        <h2 className="text-2xl font-bold text-gray-800">Battery Management</h2>
      </div>
      
      <div className="mb-6">
        <span className="text-green-600 font-semibold">Welcome</span>
        <span className="text-gray-700">
          {" "}to our Battery Management system! Track battery swaps between cold storage units.
        </span>
      </div>

      {success && (
        <div className="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded">
          {success}
        </div>
      )}
      {error && (
        <div className="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded">
          {error}
        </div>
      )}

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Available Batteries */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-lg font-semibold mb-4">Available Batteries</h3>
          {loading ? (
            <div>Loading...</div>
          ) : (
            <div className="space-y-2 max-h-96 overflow-y-auto">
              {batteries.map(battery => {
                const isSelected = selectedBatteries.find(b => b.id === battery.id);
                const unit = coldStorageUnits.find(u => u.id === battery.cold_storage_unit_id);
                return (
                  <div
                    key={battery.id}
                    className={`p-3 border rounded cursor-pointer transition ${
                      isSelected ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300'
                    }`}
                    onClick={() => handleBatteryToggle(battery)}
                  >
                    <div className="flex justify-between items-center">
                      <div>
                        <div className="font-medium">Code: {battery.unique_code}</div>
                        <div className="text-sm text-gray-600">
                          Unit: {unit ? unit.name : 'Unknown'}
                        </div>
                        <div className="text-sm text-gray-600">
                          Condition: {battery.condition}
                        </div>
                      </div>
                      <div className="text-green-600">
                        {isSelected ? '✓ Selected' : 'Select'}
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>

        {/* Selected Batteries & Form */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-lg font-semibold mb-4">Selected Batteries & Swap</h3>
          
          {selectedBatteries.length > 0 && (
            <div className="mb-4 space-y-2 max-h-48 overflow-y-auto">
              {selectedBatteries.map(battery => {
                const unit = coldStorageUnits.find(u => u.id === battery.cold_storage_unit_id);
                return (
                  <div key={battery.id} className="p-3 border rounded bg-gray-50">
                    <div className="font-medium mb-2">Code: {battery.unique_code}</div>
                    <div className="text-sm text-gray-600 mb-2">From: {unit ? unit.name : 'Unknown'}</div>
                    <div className="grid grid-cols-1 gap-2">
                      <div>
                        <label className="block text-sm font-medium mb-1">Condition Before Removal:</label>
                        <select
                          value={battery.condition_before}
                          onChange={(e) => handleBatteryChange(battery.id, 'condition_before', e.target.value)}
                          className="w-full border rounded px-2 py-1 text-sm"
                        >
                          <option value="">Select condition</option>
                          <option value="excellent">Excellent</option>
                          <option value="good">Good</option>
                          <option value="fair">Fair</option>
                          <option value="poor">Poor</option>
                          <option value="defective">Defective</option>
                        </select>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          )}

          <form onSubmit={handleSubmit}>
            <div className="mb-3">
              <label className="block font-medium mb-1">From Unit:</label>
              <select
                name="from_unit_id"
                value={form.from_unit_id}
                onChange={(e) => setForm({...form, from_unit_id: e.target.value})}
                required
                className="block w-full border rounded p-2"
              >
                <option value="">Select source unit</option>
                <option value="kibiku">KIBIKU</option>
                {coldStorageUnits.map(unit => (
                  <option key={unit.id} value={unit.id}>
                    {unit.name} - {unit.address}
                  </option>
                ))}
              </select>
            </div>
            
            <div className="mb-3">
              <label className="block font-medium mb-1">To Unit:</label>
              <select
                name="to_unit_id"
                value={form.to_unit_id}
                onChange={(e) => setForm({...form, to_unit_id: e.target.value})}
                required
                className="block w-full border rounded p-2"
              >
                <option value="">Select destination unit</option>
                <option value="kibiku">KIBIKU</option>
                {coldStorageUnits.map(unit => (
                  <option key={unit.id} value={unit.id}>
                    {unit.name} - {unit.address}
                  </option>
                ))}
              </select>
            </div>
            
            <div className="mb-4">
              <label className="block font-medium mb-1">Notes (Battery Condition upon Return to Kibiku):</label>
              <textarea
                name="notes"
                value={form.notes}
                onChange={(e) => setForm({...form, notes: e.target.value})}
                className="block w-full border rounded p-2"
                rows={3}
              />
            </div>
            
            <button
              type="submit"
              disabled={selectedBatteries.length === 0}
              className="w-full bg-green-600 text-white py-2 rounded font-semibold hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
            >
              Swap Selected Batteries
            </button>
          </form>
        </div>
      </div>
    </div>
  );
} 