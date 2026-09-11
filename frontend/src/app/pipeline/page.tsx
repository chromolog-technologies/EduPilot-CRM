"use client";

import { useState } from "react";
import AppShell from "@/components/AppShell";
import { MOCK_STUDENTS, Student } from "@/lib/api";

export default function AdmissionsPipelinePage() {
  const [students, setStudents] = useState<Student[]>(MOCK_STUDENTS);
  const [selectedStudent, setSelectedStudent] = useState<Student | null>(null);

  const phaseGroups = [
    {
      phase: "PHASE I: INQUIRY & OUTREACH",
      color: "border-indigo-200 bg-indigo-50/50 dark:border-indigo-500/30 dark:bg-indigo-500/10",
      stages: [
        { name: "New Inquiry", slug: "New Inquiry" },
        { name: "First Outreach", slug: "First Outreach" },
        { name: "Follow-up", slug: "Follow-up" },
      ],
    },
    {
      phase: "PHASE II: COUNSELING & PREP",
      color: "border-purple-200 bg-purple-50/50 dark:border-purple-500/30 dark:bg-purple-500/10",
      stages: [
        { name: "Counseling Meeting", slug: "Counseling Meeting" },
        { name: "Docs Collecting", slug: "Docs Collecting" },
      ],
    },
    {
      phase: "PHASE III: DOSSIER & ENROLLMENT",
      color: "border-emerald-200 bg-emerald-50/50 dark:border-emerald-500/30 dark:bg-emerald-500/10",
      stages: [
        { name: "Applied", slug: "Applied" },
        { name: "Payment & Enrolled", slug: "Payment & Enrolled" },
      ],
    },
  ];

  function handleStageChange(studentId: number, newStage: string) {
    setStudents((prev) =>
      prev.map((s) => (s.id === studentId ? { ...s, stage: newStage } : s))
    );
  }

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
                EduPilot Funnel Matrix
              </span>
            </div>
            <h1 className="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">Admissions Funnel</h1>
            <p className="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
              Track student progression across 3 major admissions phases with live conversion velocity.
            </p>
          </div>

          <button
            onClick={() => {
              const modalBtn = document.querySelector<HTMLButtonElement>('button:has(span:contains("Add Student"))');
              modalBtn?.click();
            }}
            className="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-4 py-2.5 text-xs font-bold text-white shadow-lg hover:opacity-95 transition-all"
          >
            <span>+</span>
            <span>Add Student Dossier</span>
          </button>
        </div>

        {/* PIPELINE FUNNEL VELOCITY BAR */}
        <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
          <div className="flex items-center justify-between text-xs font-bold mb-3">
            <span className="text-slate-700 dark:text-slate-300">Pipeline Funnel Velocity Telemetry</span>
            <span className="text-emerald-600 dark:text-emerald-400">44.5% Overall Intake Ratio</span>
          </div>

          <div className="grid grid-cols-3 gap-3 text-center text-xs">
            <div className="rounded-2xl border border-indigo-200 bg-indigo-50/70 p-3 dark:border-indigo-500/30 dark:bg-indigo-500/10">
              <span className="block text-[10px] font-extrabold text-indigo-700 dark:text-indigo-300 uppercase">PHASE I OUTREACH</span>
              <span className="font-extrabold text-slate-900 dark:text-white text-base mt-1 block">4 active</span>
              <span className="text-[10px] text-slate-500">100% Outreach velocity</span>
            </div>
            <div className="rounded-2xl border border-purple-200 bg-purple-50/70 p-3 dark:border-purple-500/30 dark:bg-purple-500/10">
              <span className="block text-[10px] font-extrabold text-purple-700 dark:text-purple-300 uppercase">PHASE II COUNSELING</span>
              <span className="font-extrabold text-slate-900 dark:text-white text-base mt-1 block">3 active</span>
              <span className="text-[10px] text-slate-500">75% Conversion rate</span>
            </div>
            <div className="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-3 dark:border-emerald-500/30 dark:bg-emerald-500/10">
              <span className="block text-[10px] font-extrabold text-emerald-700 dark:text-emerald-300 uppercase">PHASE III ENROLLMENT</span>
              <span className="font-extrabold text-slate-900 dark:text-white text-base mt-1 block">2 enrolled</span>
              <span className="text-[10px] text-slate-500">92% Dossier readiness</span>
            </div>
          </div>
        </div>

        {/* PHASE-GROUPED KANBAN BOARD */}
        <div className="space-y-6">
          {phaseGroups.map((group) => (
            <div key={group.phase} className="space-y-3">
              {/* Phase Divider Header */}
              <div className={`flex items-center justify-between rounded-2xl border ${group.color} px-4 py-2.5 text-xs font-extrabold text-slate-900 dark:text-white`}>
                <span>{group.phase}</span>
                <span className="text-[10px] text-slate-500 uppercase">Phase Milestone</span>
              </div>

              {/* Phase Columns */}
              <div className="flex gap-4 overflow-x-auto pb-4">
                {group.stages.map((stage) => {
                  const columnStudents = students.filter((s) => s.stage === stage.slug);
                  const totalFee = columnStudents.reduce((acc, s) => acc + s.consulting_fee, 0);

                  return (
                    <div
                      key={stage.slug}
                      className="flex w-84 flex-shrink-0 flex-col rounded-3xl border border-slate-200/90 bg-white/80 p-3.5 shadow-xl shadow-slate-200/40 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/60 dark:shadow-none"
                    >
                      {/* Stage Header */}
                      <div className="mb-3 flex items-center justify-between border-b border-slate-200/80 pb-2.5 px-1 dark:border-slate-800">
                        <div>
                          <h2 className="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">{stage.name}</h2>
                          <p className="text-[11px] font-extrabold text-emerald-600 dark:text-emerald-400 mt-0.5 font-mono">
                            ₹{totalFee.toLocaleString()}
                          </p>
                        </div>
                        <span className="flex h-7 w-7 items-center justify-center rounded-xl bg-indigo-100 font-bold text-indigo-700 text-xs border border-indigo-200 dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/30">
                          {columnStudents.length}
                        </span>
                      </div>

                      {/* Cards */}
                      <div className="space-y-3 min-h-[160px]">
                        {columnStudents.length === 0 ? (
                          <div className="flex h-28 items-center justify-center rounded-2xl border border-dashed border-slate-200 p-3 text-center text-xs text-slate-400 dark:border-slate-800 dark:text-slate-600">
                            No active dossiers in this step
                          </div>
                        ) : (
                          columnStudents.map((student) => (
                            <div
                              key={student.id}
                              onClick={() => setSelectedStudent(student)}
                              className="group cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-4 shadow-md transition-all duration-300 hover:-translate-y-1 hover:border-indigo-400 dark:border-slate-800/90 dark:bg-slate-900 dark:shadow-none"
                            >
                              <div className="flex items-center justify-between text-[10px] font-bold">
                                <span
                                  className={`rounded-full px-2 py-0.5 font-extrabold ${
                                    student.temperature === "Hot"
                                      ? "bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30"
                                      : "bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30"
                                  }`}
                                >
                                  {student.temperature === "Hot" ? "🔥 HOT" : "⚡ WARM"}
                                </span>
                                <span className="text-slate-500 font-semibold">{student.country || "Kerala"}</span>
                              </div>

                              <div className="mt-2.5">
                                <h3 className="text-sm font-bold text-slate-900 group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400 transition-colors">
                                  {student.first_name} {student.last_name}
                                </h3>
                                <p className="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">📍 {student.location}</p>
                              </div>

                              <div className="mt-2.5 rounded-xl border border-slate-100 bg-slate-50 px-3 py-1.5 text-xs font-bold text-indigo-700 dark:border-slate-800 dark:bg-slate-950 dark:text-indigo-300">
                                {student.desired_program}
                              </div>

                              <div className="mt-3 flex items-center justify-between border-t border-slate-100 pt-2.5 dark:border-slate-800/80">
                                <span className="text-xs font-black text-slate-900 dark:text-white font-mono">
                                  ₹{student.consulting_fee.toLocaleString()}
                                </span>

                                <div className="flex items-center gap-1.5">
                                  <a
                                    href={`https://wa.me/${student.phone.replace(/[^0-9]/g, "")}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    onClick={(e) => e.stopPropagation()}
                                    className="flex h-7 w-7 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30"
                                  >
                                    💬
                                  </a>
                                </div>
                              </div>
                            </div>
                          ))
                        )}
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Student Intelligence Modal */}
      {selectedStudent ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/75 p-4 backdrop-blur-md">
          <div className="w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-3xl border border-slate-200 bg-white p-6 text-slate-900 shadow-2xl backdrop-blur-2xl dark:border-slate-800 dark:bg-slate-900 dark:text-white">
            <div className="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
              <div>
                <span className="text-xs font-extrabold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">
                  EduPilot Intelligence • {selectedStudent.student_code}
                </span>
                <h2 className="text-xl font-bold text-slate-900 dark:text-white">
                  {selectedStudent.first_name} {selectedStudent.last_name}
                </h2>
                <p className="text-xs text-slate-500 dark:text-slate-400">{selectedStudent.location}</p>
              </div>
              <button
                onClick={() => setSelectedStudent(null)}
                className="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700"
              >
                ✕
              </button>
            </div>

            <div className="mt-4 space-y-4 text-xs">
              <div className="grid grid-cols-2 gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                <div>
                  <span className="text-slate-500 font-medium">Desired Program:</span>
                  <p className="font-bold text-slate-900 dark:text-white mt-0.5">{selectedStudent.desired_program}</p>
                </div>
                <div>
                  <span className="text-slate-500 font-medium">Consulting Fee:</span>
                  <p className="font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">
                    ₹{selectedStudent.consulting_fee.toLocaleString()}
                  </p>
                </div>
              </div>

              <div>
                <label className="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Update Stage:</label>
                <select
                  value={selectedStudent.stage}
                  onChange={(e) => {
                    handleStageChange(selectedStudent.id, e.target.value);
                    setSelectedStudent({ ...selectedStudent, stage: e.target.value });
                  }}
                  className="w-full rounded-xl border border-slate-300 bg-slate-50 p-2.5 text-xs font-bold text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                >
                  <option value="New Inquiry">New Inquiry</option>
                  <option value="First Outreach">First Outreach</option>
                  <option value="Follow-up">Follow-up</option>
                  <option value="Counseling Meeting">Counseling Meeting</option>
                  <option value="Docs Collecting">Docs Collecting</option>
                  <option value="Applied">Applied</option>
                  <option value="Payment & Enrolled">Payment & Enrolled</option>
                </select>
              </div>
            </div>

            <div className="mt-6 flex justify-end">
              <button
                onClick={() => setSelectedStudent(null)}
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
