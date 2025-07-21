import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import L from 'leaflet';
import React, { useState, useEffect } from "react";
import axios from "axios";

// Custom marker icon (fixes missing default icon issue in Leaflet)
const markerIcon = new L.Icon({
  iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
  shadowSize: [41, 41],
});

export default function MapModule() {
  const [leasedUnits, setLeasedUnits] = useState([]);
  const [selectedUnit, setSelectedUnit] = useState(null);
  const [form, setForm] = useState({
    date: '',
    time: '',
    note: '',
  });
  const [sent, setSent] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    axios.get('/api/leased-units')
      .then(response => {
        setLeasedUnits(response.data);
      })
      .catch(error => {
        setError('Failed to load leased units.');
      });
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!selectedUnit) return;

    const scheduled_for = `${form.date} ${form.time}`;

    try {
      await axios.post('/api/visits/schedule', {
        unit_id: selectedUnit.id,
        location: selectedUnit.address,
        scheduled_for: scheduled_for,
        notes: form.note,
      });
      setSent(true);
      setTimeout(() => {
        setSent(false);
        setSelectedUnit(null);
      }, 3000);
    } catch (error) {
      setError('Failed to schedule visit.');
    }
  };

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
              position={[unit.latitude, unit.longitude]}
              icon={markerIcon}
              eventHandlers={{
                click: () => {
                  setSelectedUnit(unit);
                  setForm({ date: '', time: '', note: '' });
                  setSent(false);
                }
              }}
            >
              <Popup>
                <div>
                  <div><strong>Name:</strong> {unit.name}</div>
                  <div><strong>Address:</strong> {unit.address}</div>
                  <div><strong>Lessee:</strong> {unit.lessee_name}</div>
                </div>
              </Popup>
            </Marker>
          ))}
        </MapContainer>
      </div>
      
      {selectedUnit && (
        <form
          className="mt-8 bg-white p-6 rounded shadow max-w-xl mx-auto"
          onSubmit={handleSubmit}
        >
          <div className="text-lg font-semibold mb-4 border-b pb-2">Maintenance Visit Scheduler</div>
          <div className="mb-2"><strong>Unit Name:</strong> {selectedUnit.name}</div>
          <div className="mb-2"><strong>Address:</strong> {selectedUnit.address}</div>
          <div className="mb-2"><strong>Lessee Name:</strong> {selectedUnit.lessee_name}</div>
          <div className="mb-2"><strong>Lessee Contact:</strong> {selectedUnit.lessee_contact}</div>
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
            Schedule Visit
          </button>
          {sent && (
            <div className="mt-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded">
              Maintenance visit scheduled successfully!
            </div>
          )}
        </form>
      )}
    </div>
  );
}
