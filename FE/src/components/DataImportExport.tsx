import { useState } from 'react';
import { Download, Upload } from 'lucide-react';
import api from '../utils/api';

interface DataImportExportProps {
  familyId: number;
  onImported?: () => void;
}

export default function DataImportExport({ familyId, onImported }: DataImportExportProps) {
  const [importing, setImporting] = useState(false);
  const [exporting, setExporting] = useState(false);
  const [message, setMessage] = useState('');

  const handleExport = async () => {
    setExporting(true);
    try {
      const res = await api.get(`/families/${familyId}/people`);
      const json = JSON.stringify(res.data, null, 2);
      const blob = new Blob([json], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `giapha_family_${familyId}.json`;
      a.click();
      URL.revokeObjectURL(url);
      setMessage('Xuất dữ liệu thành công!');
    } catch {
      setMessage('Lỗi khi xuất dữ liệu.');
    } finally {
      setExporting(false);
    }
  };

  const handleImport = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    setImporting(true);
    try {
      const text = await file.text();
      const data = JSON.parse(text);
      // Import each person
      for (const person of data) {
        await api.post(`/families/${familyId}/people`, {
          full_name: person.full_name,
          gender: person.gender,
          birth_date: person.birth_date,
          death_date: person.death_date,
          birth_place: person.birth_place,
          biography: person.biography,
        });
      }
      setMessage(`Nhập ${data.length} thành viên thành công!`);
      onImported?.();
    } catch {
      setMessage('Lỗi khi nhập dữ liệu. Vui lòng kiểm tra định dạng file.');
    } finally {
      setImporting(false);
      e.target.value = '';
    }
  };

  return (
    <div className="space-y-6">
      {message && (
        <p className="text-sm text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-200">
          {message}
        </p>
      )}

      <div className="flex flex-wrap gap-4">
        <button
          onClick={handleExport}
          disabled={exporting}
          className="flex items-center gap-2 px-6 py-3 bg-stone-900 text-white rounded-xl text-sm font-medium hover:bg-stone-800 transition-all shadow-sm hover:shadow active:scale-95 disabled:opacity-60"
        >
          <Download className="size-4" />
          {exporting ? 'Đang xuất...' : 'Xuất dữ liệu (.json)'}
        </button>

        <label className="flex items-center gap-2 px-6 py-3 bg-white border border-stone-200 text-stone-700 rounded-xl text-sm font-medium hover:bg-stone-50 hover:border-stone-300 transition-all shadow-sm hover:shadow cursor-pointer active:scale-95">
          <Upload className="size-4 text-stone-400" />
          {importing ? 'Đang nhập...' : 'Nhập dữ liệu (.json)'}
          <input type="file" accept=".json" className="hidden" onChange={handleImport} disabled={importing} />
        </label>
      </div>
    </div>
  );
}
