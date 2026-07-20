"use client";

import React, { useState } from "react";
import { Plus, X } from "lucide-react";
import { Button, buttonVariants } from "@/components/ui/button";
import {
	Card,
	CardDescription,
	CardFooter,
	CardHeader,
	CardTitle,
} from "@/components/ui/card";
import { ScrollArea, ScrollBar } from "@/components/ui/scroll-area";
import {
	Drawer,
	DrawerClose,
	DrawerContent,
	DrawerDescription,
	DrawerFooter,
	DrawerHeader,
	DrawerTitle,
} from "@/components/ui/drawer";

type MenuItem = {
	id: string;
	name: string;
	price: number;
	desc: string;
	image?: string;
};

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

	return (
			<div className="flex flex-col min-h-screen pb-6">
				<div className="sticky top-16 z-40 w-full bg-background/95 backdrop-blur-md border-b">
					<ScrollArea className="w-full whitespace-nowrap">
						<div className="flex w-max space-x-2 p-3 px-4">
							{MENU_CATEGORIES.map((cat) => (
									<Button
											key={cat.id}
											variant={activeCategory === cat.id ? "default" : "secondary"}
											className="rounded-full h-8 px-4 text-xs font-semibold tracking-wide transition-colors"
											onClick={() => scrollToCategory(cat.id)}
									>
										{cat.name}
									</Button>
							))}
						</div>
						<ScrollBar orientation="horizontal" className="hidden"/>
					</ScrollArea>
				</div>

				<div className="flex flex-col gap-8 px-4 mt-6">
					{MENU_CATEGORIES.map((category) => (
							<section key={category.id} id={category.id} className="scroll-mt-32">
								<h2 className="text-xl font-bold mb-4 tracking-tight">
									{category.name}
								</h2>

								<div className="grid grid-cols-2 gap-3">
									{category.items.map((item) => (
											<Card
													key={item.id}
													className="overflow-hidden flex flex-col border-muted shadow-sm hover:shadow-md transition-shadow cursor-pointer"
													onClick={() => setSelectedItem(item)}
											>
												<div className="aspect-square bg-secondary/50 relative flex items-center justify-center">
													<span className="text-muted-foreground/40 text-xs font-medium">Image</span>
												</div>

												<CardHeader className="p-3 pb-1 pointer-events-none">
													<CardTitle className="text-sm leading-tight line-clamp-1">
														{item.name}
													</CardTitle>
													<CardDescription className="text-xs line-clamp-1 mt-0.5">
														{item.desc}
													</CardDescription>
												</CardHeader>

												<CardFooter className="p-3 pt-2 mt-auto flex items-center justify-between">
                    <span className="font-bold text-sm text-primary">
                      {item.price.toLocaleString("vi-VN")}đ
                    </span>
													<Button size="icon" className="h-7 w-7 rounded-full shrink-0">
														<Plus className="w-4 h-4"/>
													</Button>
												</CardFooter>
											</Card>
									))}
								</div>
							</section>
					))}
				</div>
				<Drawer
						open={!!selectedItem}
						onOpenChange={(open) => {
							if (!open) setSelectedItem(null);
						}}
				>
					<DrawerContent className="max-h-[90vh]">
						<div className="w-full h-48 bg-secondary/50 relative flex items-center justify-center rounded-t-xl">
							<span className="text-muted-foreground font-medium">Ảnh chi tiết món</span>
							<DrawerClose
									className={buttonVariants({
										variant: "secondary",
										size: "icon",
										className: "absolute top-4 right-4 rounded-full w-8 h-8 opacity-80 backdrop-blur-md cursor-pointer"
									})}
							>
								<X className="w-4 h-4"/>
							</DrawerClose>
						</div>

						<div className="overflow-y-auto p-4 no-scrollbar">
							<DrawerHeader className="p-0 text-left mb-6">
								<DrawerTitle className="text-2xl">{selectedItem?.name}</DrawerTitle>
								<DrawerDescription className="text-sm mt-1">
									{selectedItem?.desc}
								</DrawerDescription>
							</DrawerHeader>

							<div className="flex flex-col gap-6">
								<div className="space-y-3">
									<h4 className="font-semibold text-sm">Chọn Size (Bắt buộc)</h4>
									<div className="flex gap-3">
										<Button variant="default" className="flex-1 rounded-xl h-12 border-2 border-primary">
											Size Vừa
										</Button>
										<Button variant="outline" className="flex-1 rounded-xl h-12 border-2 border-transparent">
											Size Lớn (+10.000đ)
										</Button>
									</div>
								</div>

								<div className="space-y-3">
									<h4 className="font-semibold text-sm">Lượng đá</h4>
									<div className="grid grid-cols-4 gap-2">
										{["100%", "70%", "50%", "Không đá"].map(level => (
												<Button key={level} variant="outline" className="rounded-lg h-10 text-xs">
													{level}
												</Button>
										))}
									</div>
								</div>
							</div>
						</div>

						<DrawerFooter className="border-t pt-3 pb-safe">
							<Button size="lg" className="w-full text-base font-semibold rounded-xl h-12 shadow-md">
								Thêm vào giỏ hàng - {selectedItem?.price.toLocaleString("vi-VN")}đ
							</Button>
						</DrawerFooter>
					</DrawerContent>
				</Drawer>

			</div>
	);
}