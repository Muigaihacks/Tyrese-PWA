import React, { useState, useEffect } from "react";
import axios from "axios";

export default function ToolsSparePartsReturnModule() {
  const [inventories, setInventories] = useState([]);
  const [visits, setVisits] = useState([]);
  const [selectedItems, setSelectedItems] = useState([]);
  const [form, setForm] = useState({
    visit_id: "",
    notes: "",
  });
  const [loading, setLoading] = useState(true);
  const [success, setSuccess] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    setLoading(true);
    Promise.all([
      axios.get("/api/inventories"),
      axios.get("/api/visits/dropdown"),
    ])
      .then(([invRes, visitRes]) => {
        setInventories(Array.isArray(invRes.data) ? invRes.data : []);
        setVisits(Array.isArray(visitRes.data) ? visitRes.data : []);
      })
      .catch(() => setError("Failed to load dropdown data."))
      .finally(() => setLoading(false));
  }, []);

  const handleItemToggle = (item) => {
    setSelectedItems(prev => {
      const exists = prev.find(i => i.id === item.id);
      if (exists) {
        return prev.filter(i => i.id !== item.id);
      } else {
        return [...prev, { ...item, quantity: 1, condition: '' }];
      }
    });
  };

  const handleItemChange = (itemId, field, value) => {
    setSelectedItems(prev => 
      prev.map(item => 
        item.id === itemId ? { ...item, [field]: value } : item
      )
    );
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (selectedItems.length === 0) {
      setError("Please select at least one item.");
      return;
    }

    setSuccess(null);
    setError(null);
    
    try {
      const itemsData = selectedItems.map(item => ({
        inventory_id: item.id,
        quantity: item.quantity,
        condition: item.condition
      }));

      await axios.post("/api/inventory/return", {
        ...form,
        items_data: itemsData,
        action_type: 'tools',
        location_id: 1, // Default to Kibiku
      });
      
      setSuccess("Tools & Spare Parts return successful! Page will reload in 2 seconds...");
      setForm({
        visit_id: "",
        notes: "",
      });
      setSelectedItems([]);
      
      // Auto-reload the page after 2 seconds
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    } catch {
      setError("Return failed. Please check your input.");
    }
  };

  const getItemTypeColor = (itemType) => {
    return itemType === 'tool' ? 'blue' : 'green';
  };

  const getItemTypeLabel = (itemType) => {
    return itemType === 'tool' ? 'Tool' : 'Spare Part';
  };

  return (
    <div className="p-6">
      <div className="flex items-center mb-4">
        <div className="w-8 h-8 bg-red-400 rounded mr-4" />
        <h2 className="text-2xl font-bold text-gray-800">Tools & Spare Parts Return</h2>
      </div>
      
      <div className="mb-6">
        <span className="text-red-600 font-semibold">Welcome</span>
        <span className="text-gray-700">
          {" "}to our Tools & Spare Parts return system! Return items from visits back to Kibiku.
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
        {/* Available Items */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-lg font-semibold mb-4">Items to Return</h3>
          {loading ? (
            <div>Loading...</div>
          ) : (
            <div className="space-y-2 max-h-96 overflow-y-auto">
              {inventories.map(item => {
                const isSelected = selectedItems.find(i => i.id === item.id);
                const itemColor = getItemTypeColor(item.item_type);
                const itemLabel = getItemTypeLabel(item.item_type);
                
                return (
                  <div
                    key={item.id}
                    className={`p-3 border rounded cursor-pointer transition ${
                      isSelected ? `border-${itemColor}-500 bg-${itemColor}-50` : 'border-gray-200 hover:border-gray-300'
                    }`}
                    onClick={() => handleItemToggle(item)}
                  >
                    <div className="flex justify-between items-center">
                      <div>
                        <div className="font-medium">{item.product}</div>
                        <div className="text-sm text-gray-600">Available: {item.quantity}</div>
                        <div className={`text-xs px-2 py-1 rounded-full inline-block mt-1 ${
                          itemColor === 'blue' 
                            ? 'bg-blue-100 text-blue-800' 
                            : 'bg-green-100 text-green-800'
                        }`}>
                          {itemLabel}
                        </div>
                      </div>
                      <div className={`text-${itemColor}-600`}>
                        {isSelected ? '✓ Selected' : 'Select'}
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>

        {/* Selected Items & Form */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-lg font-semibold mb-4">Selected Items & Return</h3>
          
          {selectedItems.length > 0 && (
            <div className="mb-4 space-y-2 max-h-48 overflow-y-auto">
              {selectedItems.map(item => {
                const itemColor = getItemTypeColor(item.item_type);
                const itemLabel = getItemTypeLabel(item.item_type);
                
                return (
                  <div key={item.id} className={`p-3 border rounded bg-${itemColor}-50 border-${itemColor}-200`}>
                    <div className="font-medium mb-2">{item.product}</div>
                    <div className={`text-xs px-2 py-1 rounded-full inline-block mb-2 ${
                      itemColor === 'blue' 
                        ? 'bg-blue-100 text-blue-800' 
                        : 'bg-green-100 text-green-800'
                    }`}>
                      {itemLabel}
                    </div>
                    <div className="grid grid-cols-2 gap-2">
                      <div>
                        <label className="block text-sm font-medium mb-1">Quantity:</label>
                        <input
                          type="number"
                          min="1"
                          max={item.quantity}
                          value={item.quantity}
                          onChange={(e) => handleItemChange(item.id, 'quantity', parseInt(e.target.value))}
                          className="w-full border rounded px-2 py-1 text-sm"
                        />
                      </div>
                      <div>
                        <label className="block text-sm font-medium mb-1">Condition:</label>
                        <select
                          value={item.condition}
                          onChange={(e) => handleItemChange(item.id, 'condition', e.target.value)}
                          className="w-full border rounded px-2 py-1 text-sm"
                        >
                          <option value="">Select condition</option>
                          <option value="excellent">Excellent</option>
                          <option value="good">Good</option>
                          <option value="fair">Fair</option>
                          <option value="poor">Poor</option>
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
              <label className="block font-medium mb-1">Visit:</label>
              <select
                name="visit_id"
                value={form.visit_id}
                onChange={(e) => setForm({...form, visit_id: e.target.value})}
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
            
            <div className="mb-4">
              <label className="block font-medium mb-1">Notes:</label>
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
              disabled={selectedItems.length === 0}
              className="w-full bg-red-600 text-white py-2 rounded font-semibold hover:bg-red-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
            >
              Return Selected Items
            </button>
          </form>
        </div>
      </div>
    </div>
  );
} 