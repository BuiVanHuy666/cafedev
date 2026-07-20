"use client";

import React, { useState } from "react";
import { Minus, Plus, Trash2, ShoppingBag, ArrowLeft } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import Link from "next/link";
import { NavButton } from "@/components/base/NavButton";

const MOCK_CART = [
	{
		id: "cart_1",
		productId: "c1",
		name: "Cà Phê Sữa Đá",
		options: "Size Vừa, 100% Đá",
		price: 29000,
		quantity: 2,
	},
	{
		id: "cart_2",
		productId: "t1",
		name: "Trà Đào Cam Sả",
		options: "Size Lớn, 50% Đá, Ít Ngọt",
		price: 45000,
		quantity: 1,
	},
];

export default function CartPage() {
	const [cartItems, setCartItems] = useState(MOCK_CART);

	const increaseQuantity = (id: string) => {
		setCartItems((prev) =>
				prev.map((item) =>
						item.id === id ? {
							...item,
							quantity: item.quantity + 1
						} : item
				)
		);
	};

	const decreaseQuantity = (id: string) => {
		setCartItems((prev) =>
				prev.map((item) =>
						item.id === id && item.quantity > 1
								? {
									...item,
									quantity: item.quantity - 1
								}
								: item
				)
		);
	};

	const removeItem = (id: string) => {
		setCartItems((prev) => prev.filter((item) => item.id !== id));
	};

	const totalAmount = cartItems.reduce(
			(sum, item) => sum + item.price * item.quantity,
			0
	);

	if (cartItems.length === 0) {
		return (
				<div className="flex flex-col items-center justify-center min-h-[70vh] px-4 text-center">
					<div className="w-24 h-24 bg-secondary/50 rounded-full flex items-center justify-center mb-6">
						<ShoppingBag className="w-10 h-10 text-muted-foreground"/>
					</div>
					<h2 className="text-xl font-bold tracking-tight mb-2">
						Giỏ hàng của bạn đang trống
					</h2>
					<p className="text-muted-foreground text-sm mb-8">
						Hãy quay lại thực đơn và chọn cho mình một ly nước thật ngon nhé!
					</p>
					<Link href="/">
						Quay lại Thực đơn
					</Link>
				</div>
		);
	}

	return (
			<div className="flex flex-col min-h-screen px-4 pt-6 pb-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
				<div className="flex items-center gap-3 mb-6">
					<Link href="/">
						<ArrowLeft className="w-5 h-5"/>
					</Link>
					<h1 className="text-2xl font-bold tracking-tight">Giỏ hàng</h1>
				</div>

				<div className="flex flex-col gap-4">
					{cartItems.map((item) => (
							<Card key={item.id} className="overflow-hidden border-muted shadow-sm">
								<CardContent className="p-3 flex gap-3">
									<div className="w-20 h-20 bg-secondary/50 rounded-lg shrink-0 flex items-center justify-center">
										<span className="text-muted-foreground/40 text-[10px] font-medium">Image</span>
									</div>

									<div className="flex flex-col flex-1 justify-between py-0.5">

										<div className="flex justify-between items-start">
											<div>
												<h3 className="font-semibold text-sm leading-tight">{item.name}</h3>
												<p className="text-xs text-muted-foreground mt-1 line-clamp-2">
													{item.options}
												</p>
											</div>
											<Button
													variant="ghost"
													size="icon"
													className="h-7 w-7 text-destructive hover:text-destructive hover:bg-destructive/10 -mt-1 -mr-1 shrink-0 rounded-full"
													onClick={() => removeItem(item.id)}
											>
												<Trash2 className="w-4 h-4"/>
											</Button>
										</div>

										<div className="flex justify-between items-center mt-3">
                  <span className="font-bold text-sm text-primary">
                    {item.price.toLocaleString("vi-VN")}đ
                  </span>

											<div className="flex items-center gap-2 bg-secondary/40 rounded-full p-1">
												<Button
														variant="ghost"
														size="icon"
														className="h-6 w-6 rounded-full bg-background shadow-sm hover:bg-muted"
														onClick={() => decreaseQuantity(item.id)}
												>
													<Minus className="w-3 h-3"/>
												</Button>
												<span className="text-xs font-semibold w-4 text-center">
                      {item.quantity}
                    </span>
												<Button
														variant="ghost"
														size="icon"
														className="h-6 w-6 rounded-full bg-background shadow-sm hover:bg-muted"
														onClick={() => increaseQuantity(item.id)}
												>
													<Plus className="w-3 h-3"/>
												</Button>
											</div>
										</div>

									</div>
								</CardContent>
							</Card>
					))}
				</div>

				<div className="mt-auto pt-8">
					<Card className="border-muted shadow-sm bg-secondary/20 border-dashed">
						<CardContent className="p-4 flex flex-col gap-3">
							<div className="flex justify-between text-sm text-muted-foreground">
								<span>Tạm tính</span>
								<span>{totalAmount.toLocaleString("vi-VN")}đ</span>
							</div>
							<div className="flex justify-between text-sm text-muted-foreground">
								<span>Phí dịch vụ</span>
								<span>0đ</span>
							</div>
							<div className="h-px bg-border my-1"/>
							<div className="flex justify-between text-lg font-bold">
								<span>Tổng cộng</span>
								<span className="text-primary">{totalAmount.toLocaleString("vi-VN")}đ</span>
							</div>
						</CardContent>
					</Card>

					<NavButton href="/checkout" className="w-full mt-6">
						Xác nhận thanh toán
					</NavButton>
				</div>

			</div>
	);
}