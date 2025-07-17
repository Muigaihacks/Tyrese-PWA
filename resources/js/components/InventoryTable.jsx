import React from "react";
import CheckoutForm from "./CheckoutForm";
import ReturnForm from "./ReturnForm";

const inventoryData = [
  {
    name: "Solar panels",
    dateAdded: "06/30/2025",
    condition: "New",
    locations: [
      { name: "Kibiku", quantity: 200 },
      { name: "Ruiru", quantity: 200 }
    ],
    stockLevel: "Normal"
  },
  {
    name: "Condenser",
    dateAdded: "06/20/2025",
    condition: "Used",
    locations: [
      { name: "Kibiku", quantity: 22 }
    ],
    stockLevel: "Critical"
  },
  {
    name: "Electric fans",
    dateAdded: "07/24/2025",
    condition: "Damaged",
    locations: [
      { name: "Ruiru", quantity: 200 },
      { name: "Kalimoni", quantity: 118 }
    ],
    stockLevel: "Normal"
  },
  {
    name: "Spanner",
    dateAdded: "05/16/2025",
    condition: "New",
    locations: [
      { name: "Kalimoni", quantity: 58 }
    ],
    stockLevel: "Normal"
  }
];

export default function InventoryTable() {
  return (
    <div className="bg-white rounded-lg shadow p-6 mt-6">
      <h2 className="text-2xl font-bold mb-4">Inventory Module</h2>
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
          {inventoryData.map((item, idx) => (
            <tr key={idx} className="border-t border-gray-100">
              <td className="py-2 px-4">{item.name}</td>
              <td className="py-2 px-4">{item.dateAdded}</td>
              <td className="py-2 px-4">{item.condition}</td>
              <td className="py-2 px-4">
                {item.locations.map((loc, i) => (
                  <div key={i}>
                    {loc.name} <span className="font-semibold">({loc.quantity})</span>
                  </div>
                ))}
              </td>
              <td className="py-2 px-4">
                <span
                  className={`px-4 py-1 rounded-full text-white text-sm font-semibold
                    ${item.stockLevel === 'Normal' ? 'bg-green-400' : 'bg-red-600'}`}
                >
                  {item.stockLevel}
                </span>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {/* Add forms below the table */}
      <div className="flex gap-8 mt-8">
        <CheckoutForm />
        <ReturnForm />
      </div>
    </div>
  );
}
