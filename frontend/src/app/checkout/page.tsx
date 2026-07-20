"use client";

import React, { useState } from "react";
import { ArrowLeft, Banknote, QrCode, Wallet, CheckCircle2 } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";
import Link from "next/link";
import { NavButton } from "@/components/base/NavButton";

const PAYMENT_METHODS = [
	{
		id: "bank",
		name: "Chuyển khoản ngân hàng",
		desc: "Quét mã QR (Miễn phí giao dịch)",
		icon: QrCode,
		iconColor: "text-blue-500",
		iconBg: "bg-blue-500/10"
	},
	{
		id: "momo",
		name: "Ví điện tử MoMo",
		desc: "Mở ứng dụng MoMo để thanh toán",
		icon: Wallet,
		iconColor: "text-pink-500",
		iconBg: "bg-pink-500/10"
	},
	{
		id: "cash",
		name: "Tiền mặt tại quầy",
		desc: "Thanh toán cho nhân viên khi nhận nước",
		icon: Banknote,
		iconColor: "text-green-500",
		iconBg: "bg-green-500/10"
	},
];

export default function CheckoutPage() {
	const [selectedMethod, setSelectedMethod] = useState("bank");
	const totalAmount = 74000;

	return (
			<div className="flex flex-col min-h-screen px-4 pt-6 pb-6 animate-in fade-in slide-in-from-right-4 duration-500 bg-secondary/10">
				<div className="flex items-center gap-3 mb-6">
					<Link href="/cart">
						<ArrowLeft className="w-5 h-5" />
					</Link>
					<h1 className="text-2xl font-bold tracking-tight">Thanh toán</h1>
				</div>

				<div className="flex flex-col items-center justify-center py-6 mb-6">
					<span className="text-muted-foreground text-sm font-medium mb-1">Tổng thanh toán</span>
					<span className="text-4xl font-bold text-primary tracking-tight">
          {totalAmount.toLocaleString("vi-VN")}đ
        </span>
				</div>

				<div className="flex flex-col gap-3 flex-1">
					<h2 className="font-semibold text-sm text-muted-foreground ml-1 mb-1">
						Chọn phương thức thanh toán
					</h2>

					{PAYMENT_METHODS.map((method) => {
						const isSelected = selectedMethod === method.id;
						const Icon = method.icon;

						return (
								<Card
										key={method.id}
										className={`overflow-hidden cursor-pointer transition-all duration-200 ${
												isSelected
														? "border-primary shadow-md bg-primary/5"
														: "border-border hover:border-primary/50 bg-background"
										}`}
										onClick={() => setSelectedMethod(method.id)}
								>
									<CardContent className="p-4 flex items-center gap-4">
										<div className={`w-12 h-12 rounded-xl flex items-center justify-center shrink-0 ${method.iconBg}`}>
											<Icon className={`w-6 h-6 ${method.iconColor}`} />
										</div>

										<div className="flex flex-col flex-1">
											<span className="font-semibold text-[15px]">{method.name}</span>
											<span className="text-xs text-muted-foreground mt-0.5">{method.desc}</span>
										</div>

										<div className="shrink-0 flex items-center justify-center w-6 h-6">
											{isSelected ? (
													<CheckCircle2 className="w-6 h-6 text-primary animate-in zoom-in duration-200" />
											) : (
													<div className="w-5 h-5 rounded-full border-2 border-muted-foreground/30" />
											)}
										</div>

									</CardContent>
								</Card>
						);
					})}
				</div>

				<NavButton href={'/'}>
					{selectedMethod === "cash" ? "Đặt hàng ngay" : "Thanh toán & Đặt hàng"}
				</NavButton>

			</div>
	);
}