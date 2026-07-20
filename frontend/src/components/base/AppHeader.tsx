"use client";

import React, { useState } from "react";
import { Globe, Bell, Gift, Sparkles, CheckCircle2, Megaphone, Clock } from "lucide-react";
import { Button, buttonVariants } from "@/components/ui/button";
import { Logo } from "@/components/base/Logo";
import {
	DropdownMenu,
	DropdownMenuContent,
	DropdownMenuItem,
	DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
	Drawer,
	DrawerContent,
	DrawerDescription,
	DrawerFooter,
	DrawerHeader,
	DrawerTitle,
} from "@/components/ui/drawer";
import {
	Sheet,
	SheetContent,
	SheetDescription,
	SheetHeader,
	SheetTitle,
} from "@/components/ui/sheet";

const MOCK_NOTIFICATIONS = [
	{
		id: "notif_1",
		title: "Đơn hàng #CD1092 đã sẵn sàng!",
		desc: "Đồ uống của bạn đã được pha chế xong. Vui lòng đến quầy nhận nước nhé.",
		time: "5 phút trước",
		isRead: false,
		icon: CheckCircle2,
		iconColor: "text-green-600",
		iconBg: "bg-green-600/10",
	},
	{
		id: "notif_2",
		title: "Tặng bạn Voucher 20K 🎁",
		desc: "Ưu đãi đặc biệt dành riêng cho bạn. Áp dụng cho đơn từ 50K. Hạn sử dụng: 24h.",
		time: "2 giờ trước",
		isRead: false,
		icon: Megaphone,
		iconColor: "text-primary",
		iconBg: "bg-primary/10",
	},
	{
		id: "notif_3",
		title: "Hệ thống bảo trì",
		desc: "Tính năng thanh toán qua ZaloPay sẽ tạm gián đoạn từ 00:00 đến 02:00 ngày mai.",
		time: "Hôm qua",
		isRead: true,
		icon: Clock,
		iconColor: "text-orange-500",
		iconBg: "bg-orange-500/10",
	},
];

export default function AppHeader() {
	const [isAuthDrawerOpen, setIsAuthDrawerOpen] = useState(false);
	const [isNotifOpen, setIsNotifOpen] = useState(false);

	const unreadCount = MOCK_NOTIFICATIONS.filter(n => !n.isRead).length;

	return (
			<>
				<header className="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur-md supports-backdrop-filter:bg-background/60">
					<div className="flex h-16 items-center justify-between px-4">
						<div className="flex items-center gap-2 cursor-pointer transition-opacity hover:opacity-80">
							<Logo className="w-9 h-9 text-primary" />
							<span className="text-2xl font-bold tracking-tight">cafedev</span>
						</div>

						<div className="flex items-center gap-3">
							<Button
									onClick={() => setIsAuthDrawerOpen(true)}
									className="rounded-full px-5 font-semibold shadow-sm transition-transform active:scale-95 bg-primary text-primary-foreground hover:bg-primary/90"
							>
								Nhận Ưu Đãi
							</Button>

							<DropdownMenu>
								<DropdownMenuTrigger
										className={buttonVariants({
											variant: "ghost",
											size: "icon",
											className: "rounded-full h-10 w-10 cursor-pointer"
										})}
								>
									<Globe className="w-6 h-6 text-foreground/80" />
									<span className="sr-only">Đổi ngôn ngữ</span>
								</DropdownMenuTrigger>

								<DropdownMenuContent
										align="end"
										className="w-40 rounded-xl z-100 bg-background/10 backdrop-blur-md shadow-xl border"
								>
									<DropdownMenuItem className="cursor-pointer font-medium">🇻🇳 Việt Nam</DropdownMenuItem>
									<DropdownMenuItem className="cursor-pointer font-medium">🇬🇧 English</DropdownMenuItem>
									<DropdownMenuItem className="cursor-pointer font-medium">🇨🇳 中文 (China)</DropdownMenuItem>
								</DropdownMenuContent>
							</DropdownMenu>

							<Button
									variant="ghost"
									size="icon"
									className="relative rounded-full h-10 w-10"
									onClick={() => setIsNotifOpen(true)}
							>
								<Bell className="w-5 h-5 text-foreground/80" />
								{unreadCount > 0 && (
										<span className="absolute top-0 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-destructive text-[11px] font-bold text-destructive-foreground border-2 border-background shadow-sm bg-[#fa1837]">
                                    {unreadCount}
                                </span>
								)}
								<span className="sr-only">Thông báo</span>
							</Button>
						</div>
					</div>
				</header>

				<Sheet open={isNotifOpen} onOpenChange={setIsNotifOpen}>
					<SheetContent side="right" className="w-full sm:max-w-md p-0 flex flex-col">
						<SheetHeader className="p-4 border-b text-left">
							<SheetTitle className="text-xl font-bold">Thông báo</SheetTitle>
							<SheetDescription className="text-xs">
								Cập nhật trạng thái đơn hàng và các khuyến mãi mới nhất.
							</SheetDescription>
						</SheetHeader>

						<div className="flex-1 overflow-y-auto no-scrollbar p-2">
							{MOCK_NOTIFICATIONS.map((notif) => {
								const Icon = notif.icon;
								return (
										<div
												key={notif.id}
												className={`flex gap-3 p-3 mb-1 rounded-xl cursor-pointer transition-colors hover:bg-muted/50 ${
														!notif.isRead ? "bg-primary/5" : ""
												}`}
										>
											<div className={`shrink-0 w-10 h-10 rounded-full flex items-center justify-center mt-0.5 ${notif.iconBg}`}>
												<Icon className={`w-5 h-5 ${notif.iconColor}`} />
											</div>

											<div className="flex flex-col flex-1">
												<div className="flex justify-between items-start gap-2">
													<h4 className={`text-sm leading-tight ${!notif.isRead ? "font-bold text-foreground" : "font-semibold text-muted-foreground"}`}>
														{notif.title}
													</h4>
													{!notif.isRead && (
															<div className="w-2 h-2 rounded-full bg-primary shrink-0 mt-1" />
													)}
												</div>
												<p className="text-[12px] text-muted-foreground mt-1 line-clamp-2">
													{notif.desc}
												</p>
												<span className="text-[10px] text-muted-foreground/70 mt-1.5 font-medium">
                                            {notif.time}
                                        </span>
											</div>
										</div>
								);
							})}
						</div>
					</SheetContent>
				</Sheet>

				<Drawer open={isAuthDrawerOpen} onOpenChange={setIsAuthDrawerOpen}>
					<DrawerContent className="px-4 pb-8 pt-4">
						<div className="mx-auto w-12 h-1.5 rounded-full bg-muted mb-3" />

						<DrawerHeader className="p-0 text-center mb-4">
							<div className="mx-auto w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-2">
								<Gift className="w-6 h-6 text-primary animate-bounce" />
							</div>
							<DrawerTitle className="text-lg font-bold tracking-tight">
								Đăng nhập & Nhận ưu đãi
							</DrawerTitle>
							<DrawerDescription className="text-xs mt-0.5 px-4">
								Đăng nhập để sở hữu ngay các mã giảm giá độc quyền!
							</DrawerDescription>
						</DrawerHeader>

						<div className="flex flex-col gap-2 mb-5">
							<div className="flex items-center gap-1.5 text-[11px] font-semibold text-muted-foreground ml-1">
								<Sparkles className="w-3 h-3 text-yellow-500" />
								<span>Voucher dành cho thành viên mới:</span>
							</div>

							<div className="flex items-center justify-between p-2.5 rounded-xl border border-dashed border-primary/40 bg-primary/5">
								<div className="flex items-center gap-2.5">
									<div className="w-8 h-8 rounded-lg bg-primary text-primary-foreground font-bold flex items-center justify-center text-xs shadow-xs">
										-20K
									</div>
									<div className="text-left">
										<h4 className="font-semibold text-xs leading-tight text-foreground">
											Giảm 20.000đ đơn đầu tiên
										</h4>
										<p className="text-[10px] text-muted-foreground mt-0.5">
											Đơn tối thiểu 50k • Áp dụng Cà phê máy
										</p>
									</div>
								</div>
								<span className="text-[10px] font-bold text-primary bg-primary/10 px-2 py-1 rounded-md">
                                Lưu
                            </span>
							</div>

							<div className="flex items-center justify-between p-2.5 rounded-xl border border-dashed border-orange-500/40 bg-orange-500/5">
								<div className="flex items-center gap-2.5">
									<div className="w-8 h-8 rounded-lg bg-orange-500 text-white font-bold flex items-center justify-center text-xs shadow-xs">
										FREE
									</div>
									<div className="text-left">
										<h4 className="font-semibold text-xs leading-tight text-foreground">
											Tặng 01 Topping tự chọn
										</h4>
										<p className="text-[10px] text-muted-foreground mt-0.5">
											Áp dụng cho danh mục Trà sữa size L
										</p>
									</div>
								</div>
							</div>
						</div>

						<div className="flex flex-col gap-2.5">
							<Button
									variant="outline"
									size="lg"
									className="w-full h-11 text-sm font-semibold rounded-xl border-2 bg-white text-black hover:bg-gray-50 hover:text-black shadow-xs active:scale-[0.98] transition-transform cursor-pointer"
							>
								<svg className="w-4 h-4 mr-2" viewBox="0 0 24 24">
									<path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
									<path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
									<path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
									<path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
								</svg>
								Tiếp tục với Google
							</Button>
						</div>

						<DrawerFooter className="p-0 mt-4 text-center">
							<p className="text-[10px] text-muted-foreground leading-relaxed px-4">
								Bằng việc tiếp tục, bạn đồng ý với <span className="font-semibold text-foreground underline decoration-muted-foreground/30 underline-offset-2">Điều khoản dịch vụ</span>.
							</p>
						</DrawerFooter>
					</DrawerContent>
				</Drawer>
			</>
	);
}