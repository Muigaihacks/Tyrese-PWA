import React, { useState, useEffect } from "react";
import axios from "axios";
import CheckoutForm from "./CheckoutForm";
import ReturnForm from "./ReturnForm";

export default function InventoryTable() {
  const [inventoryData, setInventoryData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    axios
      .get("/api/inventories")
      .then(response => {
        setInventoryData(response.data);
        setLoading(false);
      })
      .catch(error => {
        setError("Failed to load inventory data.");
        setLoading(false);
      });
  }, []);

  return (
    <div className="bg-white rounded-lg shadow p-6 mt-6">
      <h2 className="text-2xl font-bold mb-4">Inventory Module</h2>
      {loading && <div>Loading...</div>}
      {error && <div className="text-red-500">{error}</div>}
      {!loading && !error && (
        <table className="min-w-full border-t border-b border-gray-200">
          <thead>
            <tr className="text-left text-gray-700">
              <th className="py-2 px-4">Product</th>
              <th className="py-2 px-4">Date Added</th>
              <th className="py-2 px-4">Condition</th>
              <th className="py-2 px-4">Locations</th>
              <th className="py-2 px-4">Stock Level</th>
            </tr>
          </thead>
          <tbody>
            {inventoryData.map(item => (
              <tr key={item.id} className="border-t border-gray-100">
                <td className="py-2 px-4">{item.product}</td>
                <td className="py-2 px-4">{new Date(item.date_added).toLocaleDateString()}</td>
                <td className="py-2 px-4">{item.condition}</td>
                <td className="py-2 px-4">
                  <ul className="list-disc list-inside">
                    {item.inventory_locations.map(loc => (
                      <li key={loc.id}>
                        {loc.location.name}: <span className="font-semibold">{loc.quantity}</span>
                      </li>
                    ))}
                  </ul>
                </td>
                <td className="py-2 px-4">
                  <span
                    className={`px-3 py-1 rounded-full text-white text-xs font-semibold
                      ${item.stock_level === 'In Stock' ? 'bg-green-500' :
                        item.stock_level === 'Low Stock' ? 'bg-yellow-500' :
                        'bg-red-500'}`}
                  >
                    {item.stock_level}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
      <div className="flex gap-8 mt-8">
        <CheckoutForm />
        <ReturnForm />
      </div>
    </div>
  );
}
