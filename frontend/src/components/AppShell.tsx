"use client";

import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import AddStudentModal from "./AddStudentModal";
import { MOCK_COUNSELORS, Student } from "@/lib/api";

type AppShellProps = {
  children: React.ReactNode;
  onAddStudentSuccess?: (student: Student) => void;
};

export default function AppShell({ children, onAddStudentSuccess }: AppShellProps) {
  const pathname = usePathname();
  const router = useRouter();

  const [currentTime, setCurrentTime] = useState("");
  const [selectedCounselorId, setSelectedCounselorId] = useState<number>(2);
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [isDarkMode, setIsDarkMode] = useState(false);

  // Sync dark class on html document
  useEffect(() => {
    if (isDarkMode) {
      document.documentElement.classList.add("dark");
    } else {
      document.documentElement.classList.remove("dark");
    }
  }, [isDarkMode]);

  // Live IST Clock simulation
  useEffect(() => {
    function updateClock() {
      const now = new Date();
      const options: Intl.DateTimeFormatOptions = {
        timeZone: "Asia/Kolkata",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: true,
      };
      setCurrentTime(new Intl.DateTimeFormat("en-US", options).format(now));
    }
    updateClock();
    const interval = setInterval(updateClock, 1000);
    return () => clearInterval(interval);
  }, []);

  // Gold Version Navigation Array
  const navItems = [
    { label: "Command Deck", href: "/dashboard", icon: "📊" },
    { label: "Outreach Engine", href: "/followups", badge: "1 ready", icon: "📢" },
    { label: "Admissions Funnel", href: "/pipeline", badge: "9 active", icon: "📑" },
    { label: "Student Dossiers", href: "/students", badge: "7 follow-ups", icon: "👥" },
    { label: "Omnichannel Hub", href: "/whatsapp", badge: "Live Hub", icon: "💬" },
    { label: "Campaigns", href: "/campaigns", icon: "🎯" },
    { label: "Automations", href: "/automations", icon: "⚡" },
    { label: "Integrations", href: "/integrations", icon: "🔌" },
    { label: "AI Copywriter", href: "/ai", icon: "✨" },
    { label: "Intelligence & Reports", href: "/reports", icon: "📈" },
  ];

  return (
    <div className={`min-h-screen transition-colors duration-300 ${isDarkMode ? "dark bg-[#080C14] text-white" : "bg-slate-50/70 text-slate-900"}`}>
      {/* FLOATING TOP COMMAND HEADER */}
      <header className="sticky top-0 z-40 flex h-18 items-center justify-between border-b border-slate-200/80 bg-white/80 px-6 backdrop-blur-2xl dark:border-slate-800/80 dark:bg-slate-950/80 shadow-md">
        {/* Left: Brand Identity & IST Clock */}
        <div className="flex items-center gap-4">
          <div className="flex items-center gap-3">
            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 font-black text-white shadow-lg">
              🚀
            </div>
            <div>
              <div className="flex items-center gap-1.5">
                <span className="text-lg font-black tracking-tight text-slate-900 dark:text-white">
                  EduPilot
                </span>
                <span className="rounded-md bg-amber-500/10 px-1.5 py-0.5 text-[9px] font-extrabold text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-500/30 uppercase tracking-wider">
                  GOLD CRM
                </span>
              </div>
              <p className="text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                AI-Powered Student & Admissions CRM
              </p>
            </div>
          </div>

          <div className="hidden h-6 w-px bg-slate-200 dark:bg-slate-800 md:block" />

          {/* Live IST Clock */}
          <div className="hidden items-center gap-2 rounded-full border border-slate-200 bg-slate-100/80 px-3.5 py-1 text-xs font-medium text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 md:flex">
            <span className="h-2 w-2 rounded-full bg-emerald-500 animate-ping" />
            <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">IST:</span>
            <span className="font-mono text-slate-900 dark:text-white font-bold">{currentTime || "09:03:00 PM"}</span>
          </div>

          <span className="hidden rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-400 sm:inline-block">
            ₹ (INR)
          </span>
        </div>

        {/* Center: Advisor Switcher Pills */}
        <div className="hidden items-center gap-1.5 lg:flex">
          <span className="mr-1 text-[10px] font-extrabold tracking-widest text-slate-400 uppercase">
            ADVISOR:
          </span>
          {MOCK_COUNSELORS.map((counselor) => {
            const isSelected = selectedCounselorId === counselor.id;
            return (
              <button
                key={counselor.id}
                onClick={() => setSelectedCounselorId(counselor.id)}
                className={`flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition-all ${
                  isSelected
                    ? "border border-indigo-300 bg-indigo-50 font-bold text-indigo-700 shadow-2xs dark:border-indigo-500/50 dark:bg-indigo-600/30 dark:text-indigo-300"
                    : "text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-slate-200"
                }`}
              >
                <span className="text-xs">👤</span>
                <span>{counselor.name}</span>
              </button>
            );
          })}
        </div>

        {/* Right: AI Copilot, Theme Switcher & Add Student Button */}
        <div className="flex items-center gap-3">
          {/* Light/Dark Theme Switcher Button */}
          <button
            onClick={() => setIsDarkMode(!isDarkMode)}
            className="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-slate-700 hover:bg-slate-200 dark:border-slate-800 dark:bg-slate-900 dark:text-amber-300 dark:hover:bg-slate-800 transition-colors"
            title="Toggle Light / Dark Theme"
          >
            {isDarkMode ? "☀️" : "🌙"}
          </button>

          <Link href="/ai" className="hidden items-center gap-1.5 rounded-full border border-indigo-200 bg-indigo-50 px-3.5 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 dark:border-purple-500/30 dark:bg-purple-500/10 dark:text-purple-300 sm:flex">
            <span>✨</span>
            <span>Ask AI Copilot</span>
          </Link>

          {/* Add Student Primary Action */}
          <button
            onClick={() => setIsAddModalOpen(true)}
            className="flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-4 py-2 text-xs font-bold text-white shadow-lg hover:opacity-95 transition-all"
          >
            <span className="text-base font-normal">+</span>
            <span>Add Student</span>
          </button>
        </div>
      </header>

      {/* FLOATING CENTER NAVIGATION DOCK */}
      <div className="sticky top-20 z-30 flex justify-center px-4 py-2">
        <div className="flex flex-wrap items-center justify-center gap-1.5 rounded-full border border-slate-200/90 bg-white/90 p-1.5 backdrop-blur-2xl shadow-xl shadow-slate-200/50 dark:border-slate-800/90 dark:bg-slate-900/90 dark:shadow-slate-950/50 dock-glow">
          {navItems.map((item) => {
            const isActive = pathname === item.href;
            return (
              <Link
                key={item.href}
                href={item.href}
                className={`flex items-center gap-2 rounded-full px-3.5 py-1.5 text-xs font-bold transition-all duration-200 ${
                  isActive
                    ? "bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md shadow-indigo-600/30"
                    : "text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                }`}
              >
                <span className="text-sm">{item.icon}</span>
                <span>{item.label}</span>
                {item.badge ? (
                  <span
                    className={`rounded-full px-2 py-0.5 text-[9px] font-extrabold ${
                      isActive
                        ? "bg-white/20 text-white"
                        : "bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300"
                    }`}
                  >
                    {item.badge}
                  </span>
                ) : null}
              </Link>
            );
          })}
        </div>
      </div>

      {/* MAIN CONTENT AREA */}
      <main className="mx-auto max-w-7xl p-6">{children}</main>

      {/* Global Add Student Modal */}
      <AddStudentModal
        isOpen={isAddModalOpen}
        onClose={() => setIsAddModalOpen(false)}
        onAddStudent={(newStudent) => {
          if (onAddStudentSuccess) {
            onAddStudentSuccess(newStudent);
          }
        }}
      />
    </div>
  );
}
