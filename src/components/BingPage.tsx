import { Download, ExternalLink, Image, RefreshCw } from "lucide-react";
import { useApi } from "../hooks/useApi";
import { CardTitle, EmptyState, Status } from "./ui";

export type BingWallpaper = {
	url?: string;
	image_url?: string;
	copyright?: string;
	copyright_link?: string;
	description?: string;
	title?: string;
};

export function BingPage({ apiBase }: { apiBase: string }) {
	const bing = useApi<BingWallpaper>(apiBase, "/bing", {}, true);
	const imageUrl = bing.data?.url || bing.data?.image_url;

	const handleDownload = () => {
		if (!imageUrl) return;
		const link = document.createElement("a");
		link.href = imageUrl;
		link.download = `bing-wallpaper-${new Date().toISOString().slice(0, 10)}.jpg`;
		link.click();
	};

	return (
		<section className="page-stack">
			<div className="page-title">
				<span>
					<Image size={24} /> 每日一图
				</span>
				<Status state={bing} />
			</div>
			<article className="card bing-card">
				<CardTitle
					icon={<Image size={22} />}
					title="Bing 每日壁纸"
					right={
						<div className="button-row">
							{imageUrl && (
								<>
									<button className="outline-button" onClick={handleDownload}>
										<Download size={17} /> 下载
									</button>
									<a
										className="outline-button"
										href={imageUrl}
										target="_blank"
										rel="noreferrer"
									>
										<ExternalLink size={17} /> 原图
									</a>
								</>
							)}
							<button className="outline-button" onClick={bing.reload}>
								<RefreshCw size={17} /> 刷新
							</button>
						</div>
					}
				/>
				{bing.loading ? (
					<div className="bing-loading">
						<div className="loading-spinner" />
						<span>加载中...</span>
					</div>
				) : bing.error ? (
					<EmptyState title="加载失败" desc={bing.error} />
				) : imageUrl ? (
					<div className="bing-image-container">
						<img
							src={imageUrl}
							alt={bing.data?.title || "Bing 每日壁纸"}
							className="bing-image"
						/>
						<div className="bing-info">
							{bing.data?.title && <h3>{bing.data.title}</h3>}
							{bing.data?.description && (
								<p className="bing-description">{bing.data.description}</p>
							)}
							{bing.data?.copyright && (
								<p className="bing-copyright">
									来源:{" "}
									{bing.data.copyright_link ? (
										<a
											href={bing.data.copyright_link}
											target="_blank"
											rel="noreferrer"
										>
											{bing.data.copyright}
										</a>
									) : (
										bing.data.copyright
									)}
								</p>
							)}
						</div>
					</div>
				) : (
					<EmptyState
						title="暂无图片"
						desc="暂时无法获取 Bing 每日壁纸，请稍后重试。"
					/>
				)}
			</article>
		</section>
	);
}