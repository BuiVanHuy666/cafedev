"use client";

import React from "react";
import { Bot, Coffee, Send, ShoppingCart, Sparkles, X } from "lucide-react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import {
	Drawer,
	DrawerClose,
	DrawerContent,
	DrawerDescription,
	DrawerHeader,
	DrawerTitle,
} from "@/components/ui/drawer";
import { LiquidGlassCard } from "@/components/ui/liquid-glass";
import { ActionButton } from "@/components/base/ActionButton";
import { ScrollArea, ScrollBar } from "@/components/ui/scroll-area";

type ChatMessage = {
	id: string;
	role: "assistant" | "user";
	content: string;
};

const quickPrompts = [
	"Gợi ý món bán chạy",
	"Combo tiết kiệm cho 2 người",
	"Hỏi món ít cay",
	"Đồ uống giải nhiệt mùa hè",
	"Món tráng miệng ngọt ngào",
];

function createReply(message: string) {
	const text = message.toLowerCase();

	if (text.includes("bán chạy")) {
		return "Món bán chạy hôm nay là cà phê sữa đá, trà đào và bánh flan.";
	}

	if (text.includes("2 người") || text.includes("hai người")) {
		return "Bạn có thể thử combo 2 người: 2 món chính + 2 nước + 1 món tráng miệng để tiết kiệm hơn.";
	}

	if (text.includes("ít cay")) {
		return "Mình gợi ý các món không cay như cơm gà, mì xào hải sản hoặc salad.";
	}

	return "Mình đã nhận câu hỏi của bạn. Hãy mô tả thêm món, ngân sách hoặc khẩu vị để mình gợi ý chính xác hơn.";
}

export default function AppFooter() {
	const [chatOpen, setChatOpen] = React.useState(false);
	const [input, setInput] = React.useState("");
	const [messages, setMessages] = React.useState<ChatMessage[]>([
		{
			id: "welcome",
			role: "assistant",
			content: "Xin chào, mình là trợ lý AI của cafedev. Bạn muốn gọi món gì hôm nay?",
		},
	]);

	const pathname = usePathname();
	const isMenu = pathname === "/";
	const isCart = pathname === "/cart";

	const sendMessage = React.useCallback(
			(message: string) => {
				const trimmed = message.trim();
				if (!trimmed) return;

				const userMessage: ChatMessage = {
					id: crypto.randomUUID(),
					role: "user",
					content: trimmed,
				};

				const assistantMessage: ChatMessage = {
					id: crypto.randomUUID(),
					role: "assistant",
					content: createReply(trimmed),
				};

				setMessages((current) => [...current, userMessage, assistantMessage]);
				setInput("");
			},
			[]
	);

	return (
			<>
				<Drawer open={chatOpen} onOpenChange={setChatOpen} swipeDirection="down">
					<DrawerContent className="h-[85dvh] max-h-[85dvh] border-none">
						<div className="flex h-full flex-col overflow-hidden to-muted/20">
							<LiquidGlassCard
									glowIntensity='none'
									shadowIntensity='xs'
									borderRadius='32px 32px 0 0'
									blurIntensity='sm'
									draggable={false}
									className='border-b border-white/10 z-20 shrink-0'
							>
								<DrawerHeader className="px-5 py-4">
									<div className="flex items-start justify-between gap-5">
										<div>
											<DrawerTitle className="flex items-center gap-2 text-base font-bold">
												<Bot className="h-5 w-5 text-[#0071e3]"/>
												Trợ lý AI
											</DrawerTitle>
											<DrawerDescription className="mt-1 text-xs font-medium">
												Hỏi món, combo hoặc gợi ý theo khẩu vị của bạn.
											</DrawerDescription>
										</div>
										<DrawerClose
												render={
													<Button variant="ghost" size="icon" className="rounded-full h-8 w-8 cursor-pointer bg-black/10 hover:bg-black/20"/>
												}
										>
											<X className="h-4 w-4"/>
											<span className="sr-only">Đóng chat</span>
										</DrawerClose>
									</div>
								</DrawerHeader>
							</LiquidGlassCard>

							<div className="flex-1 min-h-0 overflow-y-auto overscroll-contain px-5 py-4 no-scrollbar">
								<div className="flex flex-col gap-4 pb-4">
									{messages.map((message) => (
											<div
													key={message.id}
													className={`flex ${message.role === "user" ? "justify-end" : "justify-start"}`}
											>
												<Card
														className={`max-w-[85%] rounded-2xl border shadow-sm ${
																message.role === "user"
																		? "border-[#0071e3]/20 bg-[#0071e3] text-white"
																		: "border-white/10 bg-secondary/40 backdrop-blur-md text-foreground"
														}`}
												>
													<CardContent className="px-4 py-3">
														<p className="text-[14px] leading-relaxed font-medium">{message.content}</p>
													</CardContent>
												</Card>
											</div>
									))}
								</div>
							</div>

							<LiquidGlassCard
									glowIntensity='none'
									borderRadius='0'
									blurIntensity='md'
									shadowIntensity='none'
									draggable={false}
									className='z-20 border-none'
							>
								<div className="px-4 py-3 pb-safe">
									<ScrollArea className="w-full whitespace-nowrap">
										<div className="flex w-max space-x-2 pb-1">
											{quickPrompts.map((prompt) => (
													<ActionButton
															variant="outline"
															key={prompt}
															onClick={() => sendMessage(prompt)}
															className="h-8 cursor-pointer border-border/50 px-3 text-[12px] font-medium text-muted-foreground hover:border-[#0071e3]/50 hover:text-[#0071e3]"
													>
														<Sparkles className="mr-1.5 h-3 w-3 text-yellow-500"/>
														{prompt}
													</ActionButton>
											))}
										</div>
										<ScrollBar orientation="horizontal" className="hidden" />
									</ScrollArea>

									<form
											className="flex items-center gap-2.5"
											onSubmit={(event) => {
												event.preventDefault();
												sendMessage(input);
											}}
									>
										<input
												value={input}
												onChange={(event) => setInput(event.target.value)}
												placeholder="Nhập câu hỏi cho AI..."
												className="h-11 flex-1 rounded-full border border-white/20 bg-secondary/30 backdrop-blur-md pl-4 pr-3 text-[13px] shadow-inner outline-none placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-[#0071e3]/20 transition-all text-foreground"
										/>

										<ActionButton
												type="submit"
												variant="default"
												className="h-11 w-11 p-0 rounded-full shrink-0 shadow-md cursor-pointer"
										>
											<Send className="h-4 w-4 ml-0.5"/> {/* ml-0.5 giúp icon Send trông cân đối hơn ở giữa hình tròn */}
											<span className="sr-only">Gửi</span>
										</ActionButton>
									</form>

								</div>
							</LiquidGlassCard>

						</div>
					</DrawerContent>
				</Drawer>

				<LiquidGlassCard
						glowIntensity='md'
						shadowIntensity='md'
						borderRadius='28px'
						blurIntensity='sm'
						draggable={false}
						className='fixed bottom-3 left-4 right-4 z-50 border border-white/15 shadow-2xl'
				>
					<nav className="w-full">
						<div className="flex h-20 items-center justify-around px-1">
							<Link
									href={'/'}
									className={`relative flex h-full flex-1 flex-col items-center justify-center gap-1 transition-all cursor-pointer mx-1 ${
											isMenu ? "text-primary font-bold" : "text-muted-foreground hover:text-foreground"
									}`}
							>
								{isMenu && (
										<span className="absolute inset-x-1 inset-y-1.5 bg-primary/20 backdrop-blur-md rounded-2xl border border-primary/40 shadow-inner -z-10 animate-in fade-in zoom-in-95 duration-200"/>
								)}
								<Coffee className="h-6 w-6"/>
								<span className="text-[11px] font-semibold">Thực đơn</span>
							</Link>

							<Link
									href="/cart"
									className={`relative flex h-full flex-1 flex-col items-center justify-center gap-1 transition-all cursor-pointer mx-1 ${
											isCart ? "text-primary font-bold" : "text-muted-foreground hover:text-foreground"
									}`}
							>
								{isCart && (
										<span className="absolute inset-x-1 inset-y-1.5 bg-primary/20 backdrop-blur-md rounded-2xl border border-primary/40 shadow-inner -z-10 animate-in fade-in zoom-in-95 duration-200"/>
								)}
								<div className="relative">
									<ShoppingCart className="h-6 w-6"/>
									<span className="absolute -right-2 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full border-background text-[9px] font-bold text-destructive-foreground bg-[#fa1837]">3</span>
								</div>
								<span className="text-[11px] font-medium">Giỏ hàng</span>
							</Link>

							<button
									type="button"
									onClick={() => setChatOpen(true)}
									className="relative flex h-full flex-1 flex-col items-center justify-center gap-1 text-orange-500 transition-colors hover:text-orange-600 cursor-pointer mx-1"
							>
								<Bot className="h-6 w-6"/>
								<span className="text-[11px] font-semibold">Trợ lý AI</span>
							</button>

						</div>
					</nav>
				</LiquidGlassCard>
			</>
	);
}