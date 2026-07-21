"use client";

import React, { useEffect, useState } from "react";
import { Minus, Plus, Search, Utensils, X } from "lucide-react";
import Image from "next/image";
import { Button } from "@/components/ui/button";
import {
	Drawer,
	DrawerClose,
	DrawerContent,
	DrawerDescription,
	DrawerTitle,
} from "@/components/ui/drawer";
import { ScrollArea, ScrollBar } from "@/components/ui/scroll-area";
import { ProductCard, MenuItem } from "@/components/base/ProductCard";
import { LiquidGlassCard } from "@/components/ui/liquid-glass";
import { ActionButton } from "@/components/base/ActionButton";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";

const MENU_CATEGORIES = [
	{
		id: "coffee",
		name: "Cà Phê Máy",
		items: [
			{
				id: "c1",
				name: "Cà Phê Sữa Đá",
				price: 29000,
				desc: "Đậm đà, béo ngậy",
				image: "00f0affc-d830-433e-9220-38bb8884f50c.webp"
			},
			{
				id: "c2",
				name: "Bạc Xỉu",
				price: 29000,
				desc: "Nhiều sữa, thơm cafe",
				image: "2d698367-c851-4186-9fbd-10ff0f1c93ba.webp"
			},
			{
				id: "c3",
				name: "Americano",
				price: 25000,
				desc: "Nguyên chất, không đường",
				image: "2f1f88d0-d2cc-4e8e-8a1e-8e79fd69cb94.webp"
			},
			{
				id: "c4",
				name: "Latte Nóng",
				price: 35000,
				desc: "Êm ái, bọt sữa mịn",
				image: "5da2db4f-882a-44c6-ba6a-bfa46c2f08b9.webp"
			},
			{
				id: "c5",
				name: "Cappuccino",
				price: 35000,
				desc: "Bọt sữa dày, vị cân bằng",
				image: "6e52fc2e-9d1a-4705-9677-5d1f4c44aa8e.webp"
			},
			{
				id: "c6",
				name: "Mocha",
				price: 39000,
				desc: "Cafe kết hợp chocolate",
				image: "9af7da2f-f16f-4151-a7aa-a9c4f43bb44e.webp"
			},
			{
				id: "c7",
				name: "Espresso Double",
				price: 30000,
				desc: "Đậm vị, dành cho người thích cafe mạnh",
				image: "019a6db0-8ea6-4ecf-b109-013d31be4447.webp"
			},
			{
				id: "c8",
				name: "Caramel Macchiato",
				price: 45000,
				desc: "Caramel ngọt nhẹ, thơm sữa",
				image: "019a6db0-8ea6-4ecf-b109-013d31be4447.webp"
			},
		],
	},
	{
		id: "tea",
		name: "Trà Trái Cây",
		items: [
			{
				id: "t1",
				name: "Trà Đào Cam Sả",
				price: 35000,
				desc: "Chua ngọt thanh mát"
			},
			{
				id: "t2",
				name: "Trà Vải Nhiệt Đới",
				price: 35000,
				desc: "Thơm mùi vải thiều"
			},
			{
				id: "t3",
				name: "Oolong Hạt Sen",
				price: 40000,
				desc: "Thanh lọc cơ thể"
			},
			{
				id: "t4",
				name: "Trà Chanh Mật Ong",
				price: 30000,
				desc: "Giải khát, thanh mát"
			},
			{
				id: "t5",
				name: "Trà Dâu Tây",
				price: 39000,
				desc: "Ngọt dịu, thơm hương dâu"
			},
			{
				id: "t6",
				name: "Trà Xoài Nhiệt Đới",
				price: 39000,
				desc: "Xoài chín kết hợp trà oolong"
			},
		],
	},
	{
		id: "milk-tea",
		name: "Trà Sữa",
		items: [
			{
				id: "m1",
				name: "Trà Sữa Trân Châu Đường Đen",
				price: 45000,
				desc: "Best seller"
			},
			{
				id: "m2",
				name: "Trà Sữa Matcha",
				price: 42000,
				desc: "Matcha Nhật Bản"
			},
			{
				id: "m3",
				name: "Trà Sữa Khoai Môn",
				price: 42000,
				desc: "Béo thơm, màu tím đẹp mắt"
			},
			{
				id: "m4",
				name: "Trà Sữa Ô Long",
				price: 39000,
				desc: "Vị trà đậm, ít ngọt"
			},
			{
				id: "m5",
				name: "Trà Sữa Socola",
				price: 45000,
				desc: "Chocolate nguyên chất"
			},
		],
	},
	{
		id: "freeze",
		name: "Đá Xay",
		items: [
			{
				id: "f1",
				name: "Cookies & Cream",
				price: 49000,
				desc: "Bánh quy Oreo xay"
			},
			{
				id: "f2",
				name: "Matcha Freeze",
				price: 49000,
				desc: "Matcha kết hợp kem tươi"
			},
			{
				id: "f3",
				name: "Chocolate Freeze",
				price: 49000,
				desc: "Chocolate đậm vị"
			},
			{
				id: "f4",
				name: "Caramel Coffee Freeze",
				price: 52000,
				desc: "Cafe đá xay cùng caramel"
			},
		],
	},
	{
		id: "juice",
		name: "Nước Ép",
		items: [
			{
				id: "j1",
				name: "Nước Ép Cam",
				price: 35000,
				desc: "Cam tươi nguyên chất"
			},
			{
				id: "j2",
				name: "Nước Ép Dứa",
				price: 35000,
				desc: "Thơm chua ngọt tự nhiên"
			},
			{
				id: "j3",
				name: "Nước Ép Dưa Hấu",
				price: 35000,
				desc: "Mát lạnh, giải nhiệt"
			},
			{
				id: "j4",
				name: "Nước Ép Cà Rốt",
				price: 39000,
				desc: "Giàu vitamin A"
			},
		],
	},
	{
		id: "cake",
		name: "Bánh Ngọt",
		items: [
			{
				id: "k1",
				name: "Tiramisu",
				price: 45000,
				desc: "Bánh Ý truyền thống"
			},
			{
				id: "k2",
				name: "Cheesecake Việt Quất",
				price: 49000,
				desc: "Mịn, béo, thơm phô mai"
			},
			{
				id: "k3",
				name: "Croissant Bơ",
				price: 35000,
				desc: "Giòn nhiều lớp"
			},
			{
				id: "k4",
				name: "Bánh Mousse Chanh Dây",
				price: 45000,
				desc: "Chua nhẹ, thơm mát"
			},
			{
				id: "k5",
				name: "Bánh Chocolate Lava Với Kem Vanilla",
				price: 59000,
				desc: "Tên sản phẩm và mô tả dài để kiểm tra UI khi text xuống nhiều dòng.",
			},
		],
	},
];

export default function Home() {
	const [activeCategory, setActiveCategory] = useState(MENU_CATEGORIES[0].id);
	const [selectedItem, setSelectedItem] = useState<MenuItem | null>(null);
	const [quantity, setQuantity] = useState<number>(1);

	const scrollToCategory = (categoryId: string) => {
		setActiveCategory(categoryId);
		const element = document.getElementById(categoryId);
		if (element) {
			element.scrollIntoView({
				behavior: "smooth",
				block: "start"
			});
		}
	};

	useEffect(() => {
		const observer = new IntersectionObserver(
				(entries) => {
					entries.forEach((entry) => {
						if (entry.isIntersecting) {
							setActiveCategory(entry.target.id);
						}
					});
				},
				{
					root: null,
					rootMargin: "-20% 0px -60% 0px",
					threshold: 0,
				}
		);

		MENU_CATEGORIES.forEach((cat) => {
			const element = document.getElementById(cat.id);
			if (element) observer.observe(element);
		});

		return () => {
			MENU_CATEGORIES.forEach((cat) => {
				const element = document.getElementById(cat.id);
				if (element) observer.unobserve(element);
			});
		};
	}, []);

	return (
			<div className="flex flex-col min-h-screen pb-6">
				<div className="sticky top-19 z-40 w-[90%] mx-auto flex items-center gap-3">
					<LiquidGlassCard
							glowIntensity='sm'
							shadowIntensity='sm'
							borderRadius='40px'
							blurIntensity='sm'
							draggable={false}
							className='flex-1 overflow-hidden'
					>
						<ScrollArea className="w-full whitespace-nowrap">
							<div className="flex w-max space-x-2 px-4 items-center h-13">
								{MENU_CATEGORIES.map((cat) => {
									const isActive = activeCategory === cat.id;

									return isActive ? (
											<LiquidGlassCard
													key={cat.id}
													glowIntensity="sm"
													shadowIntensity="sm"
													borderRadius="9999px"
													blurIntensity="sm"
													draggable={false}
													className="px-5 h-9 flex items-center justify-center cursor-pointer bg-primary/20 border border-primary/40 text-foreground shadow-md"
													onClick={() => scrollToCategory(cat.id)}
											>
												<div className="relative z-30 text-xs font-bold tracking-wide">
													{cat.name}
												</div>
											</LiquidGlassCard>
									) : (
											<Button
													key={cat.id}
													variant="secondary"
													className="rounded-full h-9 px-4 text-xs font-semibold tracking-wide transition-colors cursor-pointer bg-muted/40 hover:bg-muted/70 text-muted-foreground"
													onClick={() => scrollToCategory(cat.id)}
											>
												{cat.name}
											</Button>
									);
								})}
							</div>
							<ScrollBar orientation="horizontal" className="hidden"/>
						</ScrollArea>
					</LiquidGlassCard>

					<LiquidGlassCard
							glowIntensity='sm'
							shadowIntensity='sm'
							borderRadius='40px'
							blurIntensity='sm'
							draggable={false}
							className='w-13 h-13 shrink-0 flex items-center justify-center cursor-pointer transition-transform active:scale-95'
					>
						<Search className="w-6 h-6 text-foreground"/>
					</LiquidGlassCard>
				</div>

				<div className="flex flex-col gap-8 px-4 mt-6">
					{MENU_CATEGORIES.map((category) => (
							<section key={category.id} id={category.id} className="scroll-mt-33">
								<h2 className="text-xl font-bold mb-4 tracking-tight">
									{category.name}
								</h2>

								<div className="grid grid-cols-2 gap-3">
									{category.items.map((item) => (
											<ProductCard
													key={item.id}
													item={item}
													onSelect={(product) => setSelectedItem(product)}
											/>
									))}
								</div>
							</section>
					))}
				</div>

				<Drawer
						open={!!selectedItem}
						onOpenChange={(open) => {
							if (!open) {
								setSelectedItem(null);
								setQuantity(1);
							}
						}}
				>
					<DrawerContent className="h-[90vh] max-h-[90vh] p-0 bg-transparent border-none shadow-none flex flex-col">
						<LiquidGlassCard
								glowIntensity='xl'
								shadowIntensity='xs'
								borderRadius='32px 32px 0px 0px'
								blurIntensity='lg'
								draggable={false}
								className="h-full w-full flex flex-col overflow-hidden border border-white/20"
								innerClassName="h-full flex flex-col"
						>
							<div className="relative w-full px-6 flex flex-col items-center justify-center shrink-0">
								<DrawerClose className="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-black/20 backdrop-blur-md flex items-center justify-center transition-colors hover:bg-black/40 cursor-pointer border border-white/10">
									<X className="w-4 h-4 text-foreground"/>
								</DrawerClose>

								<div className="relative z-10 w-full pt-12 px-5 pb-4 flex flex-col shrink-0">
									<div className="flex gap-4 items-center">
										<div className="relative w-24 h-24 shrink-0 bg-secondary/40 flex items-center justify-center rounded-2xl overflow-hidden border border-white/20 shadow-xl">
											{selectedItem?.image ? (
													<Image
															src={`/products/${selectedItem.image}`}
															alt={selectedItem.name}
															fill
															className="object-cover"
													/>
											) : (
													<div className="flex flex-col items-center justify-center gap-1.5 text-muted-foreground/50">
														<Utensils className="w-6 h-6 stroke-[1.5]"/>
														<span className="text-[9px] font-medium tracking-wide">Cafedev</span>
													</div>
											)}
										</div>

										<div className="flex flex-col flex-1 min-w-0 justify-center">
											<DrawerTitle className="text-[22px] font-bold tracking-tight text-foreground text-left line-clamp-2">
												{selectedItem?.name}
											</DrawerTitle>
											<div className="flex items-end gap-2.5 mt-2.5">
												<span className="text-[#0071e3] font-bold text-[18px] leading-none">
													{selectedItem?.price.toLocaleString("vi-VN")}đ
												</span>
												<span className="text-muted-foreground/60 line-through text-[13px] font-medium leading-none mb-0.5">
													{((selectedItem?.price || 0) + 15000).toLocaleString("vi-VN")}đ
												</span>
											</div>

											<DrawerDescription className="text-[13.5px] mt-4 text-muted-foreground font-medium text-left leading-relaxed">
												{selectedItem?.desc}
											</DrawerDescription>
										</div>
									</div>
								</div>
							</div>

							<div className="flex-1 min-h-0 overflow-y-auto px-5 pt-2 pb-6 no-scrollbar relative z-10 overscroll-y-contain">
								<div className="flex flex-col gap-7">
									<div className="space-y-3">
										<Label className="font-bold text-[11px] text-muted-foreground uppercase tracking-widest">
											Chọn Size (Bắt buộc)
										</Label>
										<div className="flex gap-3">
											<ActionButton variant="default" className="flex-1">
												Size Vừa
											</ActionButton>
											<ActionButton variant="outline" className="flex-1">
												Size Lớn (+10.000đ)
											</ActionButton>
										</div>
									</div>

									<div className="space-y-3">
										<Label className="font-bold text-[11px] text-muted-foreground uppercase tracking-widest">Lượng đá</Label>
										<div className="grid grid-cols-4 gap-2.5">
											{["100%", "70%", "50%", "Không đá"].map((level, index) => (
													<ActionButton
															key={level}
															variant={index === 0 ? "default" : "outline"}
													>
														{level}
													</ActionButton>
											))}
										</div>
									</div>

									<div className="space-y-3">
										<Label htmlFor="note" className="font-bold text-[11px] text-muted-foreground uppercase tracking-widest">
											Ghi chú (Tuỳ chọn)
										</Label>
										<Textarea
												id="note"
												placeholder="Thêm trân châu, ít ngọt,..."
												className="bg-secondary/30 border border-white/20 rounded-2xl p-4 text-[13px] transition-all resize-none h-24 shadow-inner placeholder:text-muted-foreground/50 text-foreground focus-visible:ring-1 focus-visible:ring-[#0071e3]/30"
										/>
									</div>
								</div>
							</div>

							<div className="shrink-0 relative w-full p-4 pb-safe z-20 bg-background/60 backdrop-blur-2xl border-t border-white/10 shadow-[0_-10px_40px_rgba(0,0,0,0.15)]">
								<div className="flex items-center justify-between mb-3.5 px-1">
									<span className="font-bold text-[12px] text-foreground uppercase tracking-widest">
										Số lượng
									</span>
									<div className="flex items-center gap-3 bg-secondary/40 border border-white/10 rounded-full p-1 shadow-inner backdrop-blur-sm">
										<button
												onClick={() => setQuantity(Math.max(1, quantity - 1))}
												className="w-9 h-9 rounded-full bg-background/80 flex items-center justify-center text-foreground hover:bg-background transition-colors shadow-sm cursor-pointer"
										>
											<Minus className="w-4 h-4"/>
										</button>
										<span className="w-6 text-center font-bold text-[16px]">{quantity}</span>
										<button
												onClick={() => setQuantity(quantity + 1)}
												className="w-9 h-9 rounded-full bg-[#0071e3] flex items-center justify-center text-white hover:bg-[#0077ED] transition-colors shadow-sm cursor-pointer"
										>
											<Plus className="w-4 h-4"/>
										</button>
									</div>
								</div>

								<ActionButton className="w-full h-14 text-[15px] cursor-pointer shadow-lg rounded-2xl">
									Thêm vào giỏ hàng - {((selectedItem?.price || 0) * quantity).toLocaleString("vi-VN")}đ
								</ActionButton>
							</div>

						</LiquidGlassCard>
					</DrawerContent>
				</Drawer>
			</div>
	);
}