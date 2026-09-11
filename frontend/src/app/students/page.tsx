"use client";

import { useState } from "react";
import AppShell from "@/components/AppShell";
import { MOCK_STUDENTS, Student } from "@/lib/api";

export default function StudentsDirectoryPage() {
  const [students, setStudents] = useState<Student[]>(MOCK_STUDENTS);
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedStage, setSelectedStage] = useState("All Stages");
  const [selectedLocation, setSelectedLocation] = useState("All Locations");
  const [selectedPriority, setSelectedPriority] = useState("All Priority Levels");
  const [viewMode, setViewMode] = useState<"grid" | "table">("grid");
  const [activeStudent, setActiveStudent] = useState<Student | null>(null);

  const filteredStudents = students.filter((s) => {
    const matchesSearch =
      s.first_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      s.last_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      s.phone.includes(searchQuery) ||
      s.desired_program.toLowerCase().includes(searchQuery.toLowerCase());

    const matchesStage = selectedStage === "All Stages" || s.stage === selectedStage;
    const matchesLocation = selectedLocation === "All Locations" || s.location.includes(selectedLocation);
    const matchesPriority = selectedPriority === "All Priority Levels" || s.priority === selectedPriority.toLowerCase();

    return matchesSearch && matchesStage && matchesLocation && matchesPriority;
  });

  function handleAddStudentSuccess(newStudent: Student) {
    setStudents((prev) => [newStudent, ...prev]);
  }

  return (
    <AppShell onAddStudentSuccess={handleAddStudentSuccess}>
      <div className="space-y-6">
        {/* Page Header */}
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <div className="flex items-center gap-2">
              <span className="rounded-full bg-indigo-100 px-2.5 py-0.5 text-[9px] font-extrabold text-indigo-700 border border-indigo-200 uppercase tracking-wider dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/30">
                EduPilot Registry
              </span>
            </div>
            <h1 className="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">Student Dossiers</h1>
            <p className="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
              Interactive registry of all prospective students, document verification checklists, and active dossiers.
            </p>
          </div>

          <div className="flex items-center gap-3">
            {/* Dual Display Mode Switcher */}
            <div className="flex rounded-xl border border-slate-200 bg-slate-100 p-1 text-xs font-semibold dark:border-slate-800 dark:bg-slate-900">
              <button
                onClick={() => setViewMode("grid")}
                className={`rounded-lg px-3.5 py-1.5 transition-all ${
                  viewMode === "grid" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
                }`}
              >
                Grid Cards
              </button>
              <button
                onClick={() => setViewMode("table")}
                className={`rounded-lg px-3.5 py-1.5 transition-all ${
                  viewMode === "table" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
                }`}
              >
                Command Table
              </button>
            </div>

            <button
              onClick={() => {
                const modalBtn = document.querySelector<HTMLButtonElement>('button:has(span:contains("Add Student"))');
                modalBtn?.click();
              }}
              className="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-4 py-2 text-xs font-bold text-white shadow-lg hover:opacity-95 transition-all"
            >
              <span>+</span>
              <span>Add Dossier</span>
            </button>
          </div>
        </div>

        {/* Filters & Search Bar */}
        <div className="flex flex-wrap items-center justify-between gap-4 rounded-3xl border border-slate-200/90 bg-white/90 p-4 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
          <div className="relative flex-1 min-w-[280px]">
            <span className="absolute left-3.5 top-3 text-slate-400 text-xs">🔍</span>
            <input
              type="text"
              placeholder="Search by student name, phone, or course..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full rounded-2xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-xs font-medium text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-hidden dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200"
            />
          </div>

          <div className="flex flex-wrap items-center gap-2.5">
            <select
              value={selectedStage}
              onChange={(e) => setSelectedStage(e.target.value)}
              className="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-bold text-slate-700 focus:border-indigo-500 focus:outline-hidden dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300"
            >
              <option>All Stages</option>
              <option>New Inquiry</option>
              <option>First Outreach</option>
              <option>Follow-up</option>
              <option>Counseling Meeting</option>
              <option>Docs Collecting</option>
              <option>Applied</option>
              <option>Payment & Enrolled</option>
            </select>

            <select
              value={selectedLocation}
              onChange={(e) => setSelectedLocation(e.target.value)}
              className="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-bold text-slate-700 focus:border-indigo-500 focus:outline-hidden dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300"
            >
              <option>All Locations</option>
              <option>Kerala</option>
              <option>Kuwait</option>
              <option>Dubai</option>
              <option>Qatar</option>
              <option>Abu Dhabi</option>
              <option>Oman</option>
            </select>
          </div>
        </div>

        {/* DUAL DISPLAY CONTENT */}
        {viewMode === "grid" ? (
          /* GRID DOSSIER CARDS VIEW */
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {filteredStudents.map((student) => (
              <div
                key={student.id}
                onClick={() => setActiveStudent(student)}
                className="group cursor-pointer rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-indigo-400 dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none"
              >
                <div className="flex items-center justify-between">
                  <div className="flex items-center gap-3">
                    <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 font-extrabold text-white text-sm shadow-md">
                      {student.first_name.charAt(0)}
                    </div>
                    <div>
                      <h3 className="text-sm font-bold text-slate-900 group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400 transition-colors">
                        {student.first_name} {student.last_name}
                      </h3>
                      <p className="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{student.student_code}</p>
                    </div>
                  </div>

                  <span
                    className={`rounded-full px-2.5 py-0.5 text-[9px] font-bold ${
                      student.temperature === "Hot"
                        ? "bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30"
                        : "bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30"
                    }`}
                  >
                    {student.temperature === "Hot" ? "🔥 HOT" : "⚡ WARM"}
                  </span>
                </div>

                <div className="mt-4 rounded-2xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950/80">
                  <span className="block text-[10px] font-extrabold text-indigo-700 dark:text-indigo-400 uppercase">DESIRED PROGRAM</span>
                  <p className="text-xs font-bold text-slate-900 dark:text-white mt-0.5">{student.desired_program}</p>
                  <p className="text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 mt-1 font-mono">
                    Fee: ₹{student.consulting_fee.toLocaleString()}
                  </p>
                </div>

                <div className="mt-3 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                  <span>📍 {student.location}</span>
                  <span className="font-bold text-slate-800 dark:text-slate-300">{student.stage}</span>
                </div>

                <div className="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-[10px] dark:border-slate-800/80">
                  <span className="rounded-md bg-slate-100 px-2 py-0.5 font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    {student.docs_ready_count}/{student.total_docs_count} Docs Ready
                  </span>
                  <span className="text-indigo-600 dark:text-indigo-400 font-bold">Open Dossier →</span>
                </div>
              </div>
            ))}
          </div>
        ) : (
          /* COMMAND TABLE VIEW */
          <div className="overflow-hidden rounded-3xl border border-slate-200/90 bg-white/90 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs">
                <thead>
                  <tr className="border-b border-slate-100 bg-slate-50/50 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:border-slate-800 dark:bg-slate-950/60">
                    <th className="py-4 px-4">Student Dossier</th>
                    <th className="py-4 px-4">Program</th>
                    <th className="py-4 px-4">Location</th>
                    <th className="py-4 px-4">Stage</th>
                    <th className="py-4 px-4">Fee (₹)</th>
                    <th className="py-4 px-4">Advisor</th>
                    <th className="py-4 px-4 text-center">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium text-slate-700 dark:divide-slate-800/60 dark:text-slate-300">
                  {filteredStudents.map((student) => (
                    <tr
                      key={student.id}
                      onClick={() => setActiveStudent(student)}
                      className="cursor-pointer hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors"
                    >
                      <td className="py-4 px-4 font-bold text-slate-900 dark:text-white">{student.first_name} {student.last_name}</td>
                      <td className="py-4 px-4 font-bold text-indigo-700 dark:text-indigo-300">{student.desired_program}</td>
                      <td className="py-4 px-4 text-slate-500 dark:text-slate-400">{student.location}</td>
                      <td className="py-4 px-4 font-semibold text-slate-900 dark:text-white">{student.stage}</td>
                      <td className="py-4 px-4 font-black text-emerald-600 dark:text-emerald-400 font-mono">₹{student.consulting_fee.toLocaleString()}</td>
                      <td className="py-4 px-4 text-slate-800 dark:text-slate-300">{student.counselor_name}</td>
                      <td className="py-4 px-4 text-center">
                        <button className="rounded-xl bg-indigo-50 border border-indigo-200 px-3 py-1 text-[10px] font-bold text-indigo-700 dark:bg-indigo-500/20 dark:border-indigo-500/30 dark:text-indigo-300">
                          View
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </div>

      {/* Student Dossier Drawer Modal */}
      {activeStudent ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/75 p-4 backdrop-blur-md">
          <div className="w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-3xl border border-slate-200 bg-white p-6 text-slate-900 shadow-2xl backdrop-blur-2xl dark:border-slate-800 dark:bg-slate-900 dark:text-white">
            <div className="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
              <div>
                <span className="text-xs font-extrabold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">
                  EduPilot Intelligence • {activeStudent.student_code}
                </span>
                <h2 className="text-xl font-bold text-slate-900 dark:text-white">
                  {activeStudent.first_name} {activeStudent.last_name}
                </h2>
                <p className="text-xs text-slate-500 dark:text-slate-400">{activeStudent.location}</p>
              </div>
              <button
                onClick={() => setActiveStudent(null)}
                className="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700"
              >
                ✕
              </button>
            </div>

            <div className="mt-4 space-y-4 text-xs">
              <div className="grid grid-cols-2 gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                <div>
                  <span className="text-slate-500 font-medium">Program:</span>
                  <p className="font-bold text-slate-900 dark:text-white mt-0.5">{activeStudent.desired_program}</p>
                </div>
                <div>
                  <span className="text-slate-500 font-medium">Consulting Fee:</span>
                  <p className="font-bold text-emerald-600 dark:text-emerald-400 mt-0.5 font-mono">
                    ₹{activeStudent.consulting_fee.toLocaleString()}
                  </p>
                </div>
              </div>
            </div>

            <div className="mt-6 flex justify-end">
              <button
                onClick={() => setActiveStudent(null)}
                className="rounded-xl bg-slate-900 px-5 py-2 text-xs font-bold text-white hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700"
              >
                Close Dossier
              </button>
            </div>
          </div>
        </div>
      ) : null}
    </AppShell>
  );
}
