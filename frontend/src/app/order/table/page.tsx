"use client";

import React from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { Scanner } from "@yudiel/react-qr-scanner";
import { AlertCircle, QrCode } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";

export default function QRScannerPage() {
	const router = useRouter();
	const searchParams = useSearchParams();
	const showError = searchParams.get("error") === "invalid_table";

	const handleScanSuccess = (text: string) => {
		try {
			const url = new URL(text);
			if (url.pathname.includes("/order/table/")) {
				router.push(url.pathname);
			} else {
				alert("Mã QR không hợp lệ. Vui lòng quét mã trên bàn của quán!");
			}
		} catch {
			alert("Định dạng QR không đúng!");
		}
	};

	return (
			<div className="fixed inset-x-0 top-16 bottom-16 z-40 flex flex-col overflow-hidden bg-black/95 text-white">
				{showError && (
						<div className="absolute left-0 top-4 z-10 w-full px-4 animate-in fade-in slide-in-from-top-4">
							<Card className="bg-destructive/90 border-destructive-foreground/20 text-white backdrop-blur-md">
								<CardContent className="p-4 flex gap-3 items-start">
									<AlertCircle className="w-5 h-5 shrink-0 mt-0.5 text-white/90" />
									<div className="flex flex-col">
										<span className="font-semibold text-sm">Mã bàn không tồn tại</span>
										<span className="text-xs text-white/80 mt-1">
											Vui lòng hướng camera vào mã QR dán trên bàn của bạn để gọi món.
										</span>
									</div>
								</CardContent>
							</Card>
						</div>
				)}

				<div className="relative flex-1 min-h-0 overflow-hidden">
					<Scanner
							onScan={(results) => {
								if (results && results.length > 0) {
									handleScanSuccess(results[0].rawValue);
								}
							}}
							onError={(error) => console.log(error)}
							styles={{
								container: { width: '100%', height: '100%' },
								video: { objectFit: 'cover' }
							}}
					/>

					<div className="absolute inset-0 pointer-events-none flex items-center justify-center">
						<div className="w-64 h-64 border-2 border-primary/50 rounded-2xl relative shadow-[0_0_0_9999px_rgba(0,0,0,0.6)]">
							<div className="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-primary rounded-tl-xl" />
							<div className="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-primary rounded-tr-xl" />
							<div className="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-primary rounded-bl-xl" />
							<div className="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-primary rounded-br-xl" />
						</div>
					</div>
				</div>

				<div className="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-8 text-center">
					<QrCode className="w-10 h-10 mx-auto text-primary mb-3 animate-pulse" />
					<p className="text-sm text-white/70">
						Hệ thống sẽ tự động vào menu khi nhận diện được mã hợp lệ.
					</p>
				</div>

			</div>
	);
}