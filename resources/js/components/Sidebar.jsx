import { NavLink } from 'react-router-dom';
import { FaBox, FaWarehouse, FaMapMarkedAlt, FaUsers } from 'react-icons/fa';

const navItems = [
  { name: 'Inventory', path: '/inventory', icon: <FaBox size={24} /> },
  { name: 'Storage', path: '/storage', icon: <FaWarehouse size={24} /> },
  { name: 'Map', path: '/map', icon: <FaMapMarkedAlt size={24} /> },
  { name: 'Labourer', path: '/casual-labourer', icon: <FaUsers size={24} /> },
];

export default function Sidebar() {
  return (
    <div className="bg-blue-800 text-white w-20 min-h-full flex flex-col items-center py-6">
      {navItems.map((item) => (
        <NavLink
          key={item.name}
          to={item.path}
          className="w-full flex items-center mb-8 group"
          style={{ minHeight: '48px' }}
        >
          {({ isActive }) => (
            <>
              {/* Vertical line */}
              <div
                className={`h-10 w-1 rounded transition-colors duration-200 mr-2 ${
                  isActive ? 'bg-white shadow-lg' : 'bg-gray-600'
                }`}
              />
              {/* Icon and text aligned */}
              <div className="flex flex-col items-center flex-1">
                <div className="mb-1">{item.icon}</div>
                <span className="text-xs">{item.name}</span>
              </div>
            </>
          )}
        </NavLink>
      ))}
    </div>
  );
} 