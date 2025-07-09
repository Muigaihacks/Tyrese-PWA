import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import L from 'leaflet';
import React, { useState } from "react";

// Custom marker icon (fixes missing default icon issue in Leaflet)
const markerIcon = new L.Icon({
  iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
  shadowSize: [41, 41],
});

const leasedUnits = [
  {
    id: 'KE-001',
    position: [-1.286389, 36.817223], // Nairobi
    contact: '0712345678',
    location: 'Nairobi',
    status: 'Scheduled',
    lessee: 'John Doe',
    email: 'john@example.com',
  },
  {
    id: 'KE-002',
    position: [0.514277, 35.269779], // Eldoret
    contact: '0722333444',
    location: 'Eldoret',
    status: 'Not yet',
    lessee: 'Jane Smith',
    email: 'jane@example.com',
  },
  {
    id: 'KE-003',
    position: [-4.043477, 39.668206], // Mombasa
    contact: '0733555666',
    location: 'Mombasa',
    status: 'Scheduled',
    lessee: 'Peter Jones',
    email: 'peter@example.com',
  },
  {
    id: 'KE-004',
    position: [0.516667, 35.283333], // Kisumu
    contact: '0744666777',
    location: 'Kisumu',
    status: 'Not yet',
    lessee: 'Mary Brown',
    email: 'mary@example.com',
  },
];

export default function MapModule() {
  const [uniqueId, setUniqueId] = useState('');
  const [error, setError] = useState('');
  const [selectedUnit, setSelectedUnit] = useState(null);
  const [form, setForm] = useState({
    date: '',
    time: '',
    note: '',
  });
  const [sent, setSent] = useState(false);

  return (
    <div>
      <div className="w-full h-[500px] rounded-lg overflow-hidden">
        <MapContainer
          center={[-1.286389, 36.817223]}
          zoom={6}
          style={{ width: '100%', height: '100%' }}
          scrollWheelZoom={false}
        >
          <TileLayer
            attribution='&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          />
          {leasedUnits.map((unit) => (
            <Marker
              key={unit.id}
              position={unit.position}
              icon={markerIcon}
              eventHandlers={{
                click: () => {
                  setUniqueId(unit.id);
                  setError('');
                  setSelectedUnit(null);
                }
              }}
            >
              <Popup>
                <div>
                  <div><strong>UniqueID:</strong> {unit.id}</div>
                  <div><strong>Contact:</strong> {unit.contact}</div>
                  <div><strong>Location:</strong> {unit.location}</div>
                  <div><strong>Maintenance Status:</strong> {unit.status}</div>
                </div>
              </Popup>
            </Marker>
          ))}
        </MapContainer>
      </div>
      <div className="mt-8 bg-gray-50 p-6 rounded shadow max-w-xl mx-auto">
        <div className="flex items-center mb-4">
          <span className="mr-2 text-2xl">🛠️</span>
          <h3 className="text-xl font-bold">Maintenance Visit Scheduler</h3>
        </div>
        <form
          onSubmit={e => {
            e.preventDefault();
            setError('');
            const found = leasedUnits.find(unit => unit.id === uniqueId.trim());
            if (!found) {
              setError('No leased unit found with that UniqueID.');
              setSelectedUnit(null);
            } else {
              setSelectedUnit(found);
            }
          }}
          className="flex flex-col sm:flex-row items-center gap-4"
        >
          <label className="font-medium">Enter UniqueID of Unit to be Scheduled for Maintenance:</label>
          <input
            type="text"
            value={uniqueId}
            onChange={e => setUniqueId(e.target.value)}
            className="border rounded px-3 py-2"
            placeholder="e.g. KE-001"
          />
          <button
            type="submit"
            className="bg-green-600 text-white px-4 py-2 rounded font-semibold hover:bg-green-700 transition"
          >
            Select
          </button>
        </form>
        {error && <div className="text-red-600 mt-2">{error}</div>}
      </div>
      {selectedUnit && (
        <form
          className="mt-8 bg-white p-6 rounded shadow max-w-xl mx-auto"
          onSubmit={e => {
            e.preventDefault();
            setSent(true);
            // Here you would send the message to the lessee
          }}
        >
          <div className="text-lg font-semibold mb-4 border-b pb-2">Maintenance Visit Form</div>
          <div className="mb-2"><strong>Lessee Name:</strong> {selectedUnit.lessee}</div>
          <div className="mb-2"><strong>UniqueID:</strong> {selectedUnit.id}</div>
          <div className="mb-2"><strong>Contact:</strong> {selectedUnit.contact}</div>
          <div className="mb-2"><strong>Email:</strong> {selectedUnit.email}</div>
          <div className="mb-2"><strong>Location:</strong> {selectedUnit.location}</div>
          <div className="mb-4">
            <label className="block font-medium mb-1">Date:</label>
            <input
              type="date"
              className="w-full border rounded px-3 py-2"
              value={form.date}
              onChange={e => setForm(f => ({ ...f, date: e.target.value }))}
              required
            />
          </div>
          <div className="mb-4">
            <label className="block font-medium mb-1">Time:</label>
            <input
              type="time"
              className="w-full border rounded px-3 py-2"
              value={form.time}
              onChange={e => setForm(f => ({ ...f, time: e.target.value }))}
              required
            />
          </div>
          <div className="mb-6">
            <label className="block font-medium mb-1">Additional Note:</label>
            <textarea
              className="w-full border rounded px-3 py-2"
              value={form.note}
              onChange={e => setForm(f => ({ ...f, note: e.target.value }))}
              rows={3}
            />
          </div>
          <button
            type="submit"
            className="w-full bg-green-600 text-white py-2 rounded font-semibold hover:bg-green-700 transition"
          >
            Send
          </button>
          {sent && (
            <div className="mt-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded">
              Maintenance visit scheduled and message sent to lessee!
            </div>
          )}
        </form>
      )}
    </div>
  );
}
