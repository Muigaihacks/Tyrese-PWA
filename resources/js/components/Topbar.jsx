export default function Topbar() {
  return (
    <div className="flex items-center justify-between bg-white h-24 py-4 px-8 shadow-sm w-full">
      {/* Hamburger menu */}
      <button className="mr-4 focus:outline-none">
        <svg className="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
          <line x1="4" y1="7" x2="20" y2="7" />
          <line x1="4" y1="12" x2="20" y2="12" />
          <line x1="4" y1="17" x2="20" y2="17" />
        </svg>
      </button>
      {/* Right side actions */}
      <div className="flex items-center">
        <button className="mr-4">
          {/* Search icon */}
          <svg className="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
        </button>
        <button className="mr-4">
          {/* Notification bell */}
          <svg className="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </button>
        <div className="flex items-center">
          {/* Avatar placeholder */}
          <img
            src="images/avatar.jpg"
            alt="Profile"
            className="w-8 h-8 rounded-full mr-2 object-cover"
          />
          <span className="font-medium text-gray-700">Muigai</span>
        </div>
      </div>
    </div>
  );
} 