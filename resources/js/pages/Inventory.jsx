import React, { useState } from 'react';
import ToolsSparePartsModule from '../components/ToolsSparePartsModule';
import ToolsSparePartsReturnModule from '../components/ToolsSparePartsReturnModule';
import BatteryManagementModule from '../components/BatteryManagementModule';

export default function Inventory() {
  const [activeTab, setActiveTab] = useState('tools');

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="p-6">
        <div className="mb-6">
          <h1 className="text-3xl font-bold text-gray-900 mb-2">Inventory Management</h1>
          <p className="text-gray-600">Manage tools, spare parts, and battery tracking</p>
        </div>

        {/* Tab Navigation */}
        <div className="border-b border-gray-200 mb-6">
          <nav className="-mb-px flex space-x-8">
            <button
              onClick={() => setActiveTab('tools')}
              className={`py-2 px-1 border-b-2 font-medium text-sm ${
                activeTab === 'tools'
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              }`}
            >
              <div className="flex items-center">
                <div className="w-5 h-5 bg-blue-400 rounded mr-2"></div>
                Tools & Spare Parts
              </div>
            </button>
            <button
              onClick={() => setActiveTab('return')}
              className={`py-2 px-1 border-b-2 font-medium text-sm ${
                activeTab === 'return'
                  ? 'border-red-500 text-red-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              }`}
            >
              <div className="flex items-center">
                <div className="w-5 h-5 bg-red-400 rounded mr-2"></div>
                Return Items
              </div>
            </button>
            <button
              onClick={() => setActiveTab('batteries')}
              className={`py-2 px-1 border-b-2 font-medium text-sm ${
                activeTab === 'batteries'
                  ? 'border-green-500 text-green-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              }`}
            >
              <div className="flex items-center">
                <div className="w-5 h-5 bg-green-400 rounded mr-2"></div>
                Battery Management
              </div>
            </button>
          </nav>
        </div>

        {/* Tab Content */}
        <div>
          {activeTab === 'tools' && <ToolsSparePartsModule />}
          {activeTab === 'return' && <ToolsSparePartsReturnModule />}
          {activeTab === 'batteries' && <BatteryManagementModule />}
        </div>
      </div>
    </div>
  );
} 