"use client";

import { useState } from "react";
import AppShell from "@/components/AppShell";
import { ChatConversation, MOCK_CONVERSATIONS, MOCK_STUDENTS, Student } from "@/lib/api";

export default function WhatsAppInboxPage() {
  const [conversations, setConversations] = useState<ChatConversation[]>(MOCK_CONVERSATIONS);
  const [activeChatId, setActiveChatId] = useState<number>(1);
  const [activeTab, setActiveTab] = useState<"all" | "my" | "needs_reply">("all");
  const [searchQuery, setSearchQuery] = useState("");
  const [messageInput, setMessageInput] = useState(
    "Hi Arjun! This is your counselor from EduPilot CRM 😊 Just checking in, have you collected the 12th original marksheet? We need that to finalize your application dossier."
  );
  const [selectedLanguage, setSelectedLanguage] = useState<"EN" | "ML" | "HI">("EN");

  const activeChat = conversations.find((c) => c.id === activeChatId) ?? conversations[0];
  const activeStudent: Student =
    MOCK_STUDENTS.find((s) => s.id === activeChat.student_id) ?? MOCK_STUDENTS[0];

  const filteredConversations = conversations.filter((c) => {
    if (activeTab === "needs_reply" && !c.needs_reply) return false;
    return (
      c.student_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      c.desired_program.toLowerCase().includes(searchQuery.toLowerCase())
    );
  });

  function handleSendMessage() {
    if (!messageInput.trim()) return;

    const newMsg = {
      id: Date.now(),
      sender: "counselor" as const,
      text: messageInput,
      timestamp: "Just now",
      status: "sent" as const,
    };

    setConversations((prev) =>
      prev.map((c) =>
        c.id === activeChatId
          ? {
              ...c,
              last_message: messageInput,
              last_time: "Just now",
              needs_reply: false,
              messages: [...c.messages, newMsg],
            }
          : c
      )
    );

    setMessageInput("");
  }

  function handleTemplateClick(templateName: string) {
    if (templateName === "First Outreach") {
      setMessageInput(
        `Hi ${activeStudent.first_name}! Thanks for reaching out to EduPilot. We offer direct university admission for ${activeStudent.desired_program}.`
      );
    } else if (templateName === "Missing Docs") {
      setMessageInput(
        `Hi ${activeStudent.first_name}! EduPilot is missing your 12th marksheet and passport copy to process your ${activeStudent.desired_program} dossier.`
      );
    } else if (templateName === "Call Reminder") {
      setMessageInput(
        `Hi ${activeStudent.first_name}! EduPilot reminder: your student counseling session is scheduled for today.`
      );
    } else if (templateName === "Fee Breakdown") {
      setMessageInput(
        `Hi ${activeStudent.first_name}! The total consulting fee for ${activeStudent.desired_program} is ₹${activeStudent.consulting_fee.toLocaleString()}.`
      );
    }
  }

  return (
    <AppShell>
      <div className="mx-auto max-w-7xl">
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-12">
          {/* LEFT COLUMN: CHAT CONVERSATIONS LIST (3 COLS) */}
          <div className="lg:col-span-3 rounded-3xl border border-slate-200/90 bg-white/90 p-4 shadow-xl shadow-slate-200/50 backdrop-blur-xl space-y-4 dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <div className="flex items-center justify-between">
              <div>
                <h1 className="text-base font-black text-slate-900 dark:text-white">Omnichannel Hub</h1>
                <p className="text-[10px] text-slate-500 dark:text-slate-400">EduPilot WhatsApp & Meta Inbox</p>
              </div>
              <span className="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[9px] font-bold text-emerald-700 border border-emerald-200 animate-pulse dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30">
                LIVE ({conversations.length})
              </span>
            </div>

            {/* Filter Tabs */}
            <div className="flex gap-1 rounded-2xl bg-slate-100 p-1 text-xs font-semibold dark:bg-slate-950">
              <button
                onClick={() => setActiveTab("all")}
                className={`flex-1 rounded-xl py-1 text-center transition-all ${
                  activeTab === "all" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
                }`}
              >
                All ({conversations.length})
              </button>
              <button
                onClick={() => setActiveTab("my")}
                className={`flex-1 rounded-xl py-1 text-center transition-all ${
                  activeTab === "my" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
                }`}
              >
                My Leads
              </button>
              <button
                onClick={() => setActiveTab("needs_reply")}
                className={`flex-1 rounded-xl py-1 text-center transition-all ${
                  activeTab === "needs_reply" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
                }`}
              >
                Needs Reply
              </button>
            </div>

            {/* Conversation List Items */}
            <div className="space-y-2 max-h-[600px] overflow-y-auto">
              {filteredConversations.map((chat) => {
                const isActive = chat.id === activeChatId;
                return (
                  <div
                    key={chat.id}
                    onClick={() => setActiveChatId(chat.id)}
                    className={`cursor-pointer rounded-2xl p-3 border transition-all ${
                      isActive
                        ? "border-indigo-300 bg-indigo-50/80 shadow-md dark:border-indigo-500/50 dark:bg-indigo-600/20"
                        : "border-slate-100 bg-slate-50/60 hover:bg-slate-100 dark:border-slate-800/80 dark:bg-slate-950/60 dark:hover:bg-slate-800/50"
                    }`}
                  >
                    <div className="flex items-start justify-between">
                      <div className="flex items-center gap-2.5">
                        <div className="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 font-bold text-white text-xs shadow-md">
                          {chat.student_name.charAt(0)}
                        </div>
                        <div>
                          <h3 className="text-xs font-bold text-slate-900 dark:text-white">{chat.student_name}</h3>
                          <span className="text-[10px] font-semibold text-indigo-600 dark:text-indigo-400">
                            {chat.desired_program}
                          </span>
                        </div>
                      </div>
                    </div>

                    <p className="mt-2 line-clamp-1 text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                      {chat.last_message}
                    </p>
                  </div>
                );
              })}
            </div>
          </div>

          {/* MIDDLE COLUMN: ACTIVE CHAT THREAD WORKSPACE (6 COLS) */}
          <div className="lg:col-span-6 flex flex-col justify-between rounded-3xl border border-slate-200/90 bg-white/90 p-4 shadow-xl shadow-slate-200/50 backdrop-blur-xl space-y-4 dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            {/* Active Header Bar */}
            <div className="border-b border-slate-100 pb-3 dark:border-slate-800">
              <div className="flex flex-wrap items-center justify-between gap-2">
                <div className="flex items-center gap-3">
                  <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 font-bold text-white text-sm shadow-md">
                    {activeChat.student_name.charAt(0)}
                  </div>
                  <div>
                    <div className="flex items-center gap-2">
                      <h2 className="text-sm font-bold text-slate-900 dark:text-white">
                        {activeChat.student_name}
                      </h2>
                      <span className="rounded-full bg-rose-100 px-2 py-0.5 text-[9px] font-bold text-rose-700 border border-rose-200 dark:bg-rose-500/20 dark:text-rose-300 dark:border-rose-500/30">
                        {activeStudent.temperature === "Hot" ? "🔥 HOT" : "⚡ WARM"}
                      </span>
                    </div>
                    <p className="mt-0.5 text-[11px] text-slate-500 dark:text-slate-400">
                      {activeChat.desired_program} • {activeChat.location} • WhatsApp: {activeStudent.phone}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            {/* Chat Message Stream */}
            <div className="flex-1 space-y-3 p-3 min-h-[300px] max-h-[420px] overflow-y-auto bg-slate-50 border border-slate-100 rounded-2xl dark:bg-slate-950/70 dark:border-slate-800">
              {activeChat.messages.map((msg) => (
                <div
                  key={msg.id}
                  className={`flex ${msg.sender === "counselor" ? "justify-end" : "justify-start"}`}
                >
                  <div
                    className={`max-w-md rounded-2xl p-3.5 text-xs shadow-md leading-relaxed ${
                      msg.sender === "counselor"
                        ? "bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-br-none"
                        : "bg-white border border-slate-200 text-slate-900 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-200 rounded-bl-none"
                    }`}
                  >
                    <p>{msg.text}</p>
                    <div className="mt-1.5 flex items-center justify-end gap-1 text-[9px] opacity-75">
                      <span>{msg.timestamp}</span>
                      {msg.sender === "counselor" ? <span>✓✓</span> : null}
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {/* Bottom Message Editor */}
            <div className="border-t border-slate-100 pt-3 space-y-3 dark:border-slate-800">
              <div className="flex flex-wrap items-center justify-between gap-2">
                <span className="text-[11px] font-bold text-slate-700 dark:text-slate-300">
                  EduPilot Message Editor <span className="text-indigo-600 dark:text-indigo-400">for {activeChat.student_name}</span>
                </span>

                {/* Quick Template Chips */}
                <div className="flex flex-wrap items-center gap-1.5">
                  {(["First Outreach", "Missing Docs", "Call Reminder", "Fee Breakdown"] as const).map(
                    (tmp) => (
                      <button
                        key={tmp}
                        onClick={() => handleTemplateClick(tmp)}
                        className="rounded-full border border-indigo-200 bg-indigo-50 px-2.5 py-0.5 text-[10px] font-semibold text-indigo-700 hover:bg-indigo-100 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300"
                      >
                        ⚡ {tmp}
                      </button>
                    )
                  )}

                  {/* Language Selector */}
                  <div className="flex rounded-md border border-slate-200 bg-slate-100 p-0.5 text-[10px] font-bold dark:border-slate-800 dark:bg-slate-950">
                    {(["EN", "ML", "HI"] as const).map((lang) => (
                      <button
                        key={lang}
                        onClick={() => setSelectedLanguage(lang)}
                        className={`px-1.5 py-0.5 rounded-xs ${
                          selectedLanguage === lang ? "bg-indigo-600 text-white shadow-xs" : "text-slate-500"
                        }`}
                      >
                        {lang}
                      </button>
                    ))}
                  </div>
                </div>
              </div>

              <textarea
                rows={3}
                value={messageInput}
                onChange={(e) => setMessageInput(e.target.value)}
                className="w-full rounded-2xl border border-slate-200 bg-slate-50 p-3.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-hidden dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                placeholder="Type your EduPilot WhatsApp message..."
              />

              <div className="flex items-center justify-between">
                <button
                  onClick={() => alert("Copied EduPilot template message!")}
                  className="rounded-xl border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300"
                >
                  Copy
                </button>

                <button
                  onClick={handleSendMessage}
                  className="flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-5 py-2 text-xs font-bold text-white shadow-lg hover:opacity-90"
                >
                  <span>1-Click Send via WhatsApp</span>
                  <span>🚀</span>
                </button>
              </div>
            </div>
          </div>

          {/* RIGHT COLUMN: STUDENT SUMMARY PANEL (3 COLS) */}
          <div className="lg:col-span-3 rounded-3xl border border-slate-200/90 bg-white/90 p-4 shadow-xl shadow-slate-200/50 backdrop-blur-xl space-y-4 dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <h2 className="text-xs font-bold tracking-widest text-slate-400 uppercase border-b border-slate-100 pb-2 dark:border-slate-800">
              STUDENT DOSSIER SUMMARY
            </h2>

            <div className="space-y-3 text-xs">
              <div>
                <span className="block text-[10px] font-bold uppercase text-slate-400">
                  DESIRED PROGRAM
                </span>
                <p className="font-black text-indigo-600 dark:text-indigo-300 text-sm mt-0.5">{activeStudent.desired_program}</p>
              </div>

              <div>
                <span className="block text-[10px] font-bold uppercase text-slate-400">
                  CONSULTING FEE
                </span>
                <p className="font-extrabold text-emerald-600 dark:text-emerald-400 font-mono mt-0.5">
                  ₹{activeStudent.consulting_fee.toLocaleString()}
                </p>
              </div>

              <div>
                <span className="block text-[10px] font-bold uppercase text-slate-400">
                  LOCATION / ORIGIN
                </span>
                <p className="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{activeStudent.location}</p>
              </div>

              <div>
                <span className="block text-[10px] font-bold uppercase text-slate-400">
                  CURRENT STAGE
                </span>
                <p className="font-bold text-indigo-600 dark:text-indigo-400 mt-0.5">{activeStudent.stage}</p>
              </div>

              {/* Document Checklist Sidebar */}
              <div className="border-t border-slate-100 pt-3 dark:border-slate-800">
                <span className="block text-[10px] font-bold uppercase text-slate-400 mb-2">
                  DOCUMENT CHECKLIST ({activeStudent.docs_ready_count}/{activeStudent.total_docs_count})
                </span>
                <div className="space-y-2">
                  {activeStudent.document_checklist.map((doc) => (
                    <div key={doc.id} className="flex items-center gap-2 text-[11px]">
                      {doc.status === "verified" ? (
                        <span className="font-bold text-emerald-600 dark:text-emerald-400">✓</span>
                      ) : (
                        <span className="font-bold text-rose-500 dark:text-rose-400">✗</span>
                      )}
                      <span
                        className={
                          doc.status === "verified" ? "text-slate-800 dark:text-slate-200 font-medium" : "text-slate-400"
                        }
                      >
                        {doc.name}
                      </span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppShell>
  );
}
