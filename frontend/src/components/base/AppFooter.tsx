"use client";

import React from "react";
import { Bot, Coffee, Send, ShoppingCart, Sparkles, X } from "lucide-react";
import Link from "next/link";
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

type ChatMessage = {
	id: string;
	role: "assistant" | "user";
	content: string;
};

const quickPrompts = [
	"Gợi ý món bán chạy",
	"Combo tiết kiệm cho 2 người",
	"Hỏi món ít cay",
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
					<DrawerContent className="h-[0dvh] max-h-[80dvh] overflow-hidden rounded-t-3xl border-border/70 bg-background p-0 shadow-2xl">
						<div className="flex h-full flex-col overflow-hidden bg-linear-to-b from-background via-background to-muted/30">
							<DrawerHeader className="border-b border-border/70 bg-background/95 px-4 py-4 backdrop-blur">
								<div className="flex items-start justify-between gap-3">
									<div>
										<DrawerTitle className="flex items-center gap-2 text-base">
											<Bot className="h-5 w-5 text-primary"/>
											Trợ lý AI
										</DrawerTitle>
										<DrawerDescription className="mt-1">
											Hỏi món, combo hoặc gợi ý theo khẩu vị của bạn.
										</DrawerDescription>
									</div>
									<DrawerClose
											render={
												<Button variant="ghost" size="icon-sm" className="rounded-full"/>
											}
									>
										<X className="h-4 w-4"/>
										<span className="sr-only">Đóng chat</span>
									</DrawerClose>
								</div>
							</DrawerHeader>

							<div className="flex-1 min-h-0 overflow-y-auto overscroll-contain px-4 py-3">
								<div className="flex flex-col gap-3 pb-4">
									{messages.map((message) => (
											<div
													key={message.id}
													className={`flex ${message.role === "user" ? "justify-end" : "justify-start"}`}
											>
												<Card
														className={`max-w-[85%] rounded-2xl border shadow-sm ${
																message.role === "user"
																		? "border-primary/20 bg-primary text-primary-foreground"
																		: "border-border/70 bg-card text-card-foreground"
														}`}
												>
													<CardContent className="px-4 py-3">
														<p className="text-sm leading-relaxed">{message.content}</p>
													</CardContent>
												</Card>
											</div>
									))}
								</div>
							</div>

							<div className="border-t border-border/70 bg-background/95 px-4 py-3 backdrop-blur">
								<div className="mb-3 flex flex-wrap gap-2">
									{quickPrompts.map((prompt) => (
											<Button
													key={prompt}
													type="button"
													variant="secondary"
													size="sm"
													className="rounded-full bg-muted text-foreground hover:bg-muted/80"
													onClick={() => sendMessage(prompt)}
											>
												<Sparkles className="h-3.5 w-3.5"/>
												{prompt}
											</Button>
									))}
								</div>

								<form
										className="flex items-center gap-2"
										onSubmit={(event) => {
											event.preventDefault();
											sendMessage(input);
										}}
								>
									<input
											value={input}
											onChange={(event) => setInput(event.target.value)}
											placeholder="Nhập câu hỏi cho AI..."
											className="h-11 flex-1 rounded-xl border border-input bg-card px-3 text-sm shadow-sm outline-none ring-0 placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-3 focus-visible:ring-ring/50"
									/>
									<Button type="submit" size="icon" className="h-11 w-11 rounded-xl">
										<Send className="h-4 w-4"/>
										<span className="sr-only">Gửi</span>
									</Button>
								</form>
							</div>
						</div>
					</DrawerContent>
				</Drawer>

				<nav className="fixed bottom-0 left-0 z-50 w-full border-t bg-background/95 pb-safe backdrop-blur-md supports-backdrop-filter:bg-background/60">
					<div className="flex h-16 items-center justify-around px-2">

						<button className="flex h-full flex-1 flex-col items-center justify-center gap-1 text-primary">
							<Coffee className="h-6 w-6"/>
							<span className="text-[11px] font-semibold">Thực đơn</span>
						</button>

						<Link href="/cart"
						      className="relative flex h-full flex-1 flex-col items-center justify-center gap-1 text-muted-foreground transition-colors hover:text-foreground"
						>
							<div className="relative">
								<ShoppingCart className="h-6 w-6"/>
								<span className="absolute -right-2 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full border-2 border-background text-[9px] font-bold text-destructive-foreground bg-[#fa1837]">
                            3
                        </span>
							</div>
							<span className="text-[11px] font-medium">Giỏ hàng</span>
						</Link>

						<button
								type="button"
								onClick={() => setChatOpen(true)}
								className="flex h-full flex-1 flex-col items-center justify-center gap-1 text-orange-500 transition-colors hover:text-orange-600"
						>
							<Bot className="h-6 w-6"/>
							<span className="text-[11px] font-semibold">Trợ lý AI</span>
						</button>

					</div>
				</nav>
			</>
	);
}