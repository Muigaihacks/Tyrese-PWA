import Sidebar from './Sidebar';
import Topbar from './Topbar';

export default function DashboardLayout({ children }) {
  return (
    <div className="h-screen bg-gray-100 flex flex-col">
      <Topbar />
      <div className="flex flex-1">
        <Sidebar />
        <main className="flex-1 p-8 overflow-y-auto bg-gray-100">{children}</main>
      </div>
    </div>
  );
} 