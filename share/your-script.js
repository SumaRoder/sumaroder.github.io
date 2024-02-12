// 获取画布和绘图上下文
const canvas = document.getElementById('rainCanvas');
const ctx = canvas.getContext('2d');

// 定义雨滴粒子类
class Raindrop {
    constructor(x, y) {
        this.x = x;
        this.y = y;
        this.size = Math.random() * 5 + 3; // 随机生成雨滴大小
        this.speed = Math.random() * 5 + 2; // 随机生成下落速度
        this.opacity = Math.random() * 0.5 + 0.3; // 随机生成透明度
    }
    
    update() {
        this.y += this.speed;
        
        // 如果雨滴超出画布，则重置位置
        if (this.y > canvas.height) {
            this.y = 0;
            this.x = Math.random() * canvas.width;
        }
    }
    
    draw() {
        ctx.fillStyle = `rgba(0, 0, 255, ${this.opacity})`;
        ctx.fillRect(this.x, this.y, this.size, this.size);
    }
}

// 存储雨滴粒子的数组
let raindrops = [];

// 创建雨滴粒子
function createRaindrop() {
    const x = Math.random() * canvas.width; // 随机生成 x 坐标
    const y = -Math.random() * 500; // 设置初始 y 坐标在画布上方
    const raindrop = new Raindrop(x, y);
    raindrops.push(raindrop);
}

// 更新雨滴粒子的位置
function updateRaindrops() {
    for (let i = 0; i < raindrops.length; i++) {
        const raindrop = raindrops[i];
        raindrop.update();
    }
}

// 清空画布
function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

// 绘制雨滴粒子
function drawRaindrops() {
    for (let i = 0; i < raindrops.length; i++) {
        const raindrop = raindrops[i];
        raindrop.draw();
    }
}

// 绘制雨滴之间的融合效果
function drawMerging() {
    for (let i = 0; i < raindrops.length; i++) {
        const raindrop1 = raindrops[i];
        for (let j = i + 1; j < raindrops.length; j++) {
            const raindrop2 = raindrops[j];
            const distance = Math.sqrt((raindrop1.x - raindrop2.x) ** 2 + (raindrop1.y - raindrop2.y) ** 2);
            
            // 如果雨滴之间的距离小于一定范围，则进行融合效果
            if (distance < 10) {
                const mergedSize = raindrop1.size + raindrop2.size;
                const mergedOpacity = Math.max(raindrop1.opacity, raindrop2.opacity);
                
                // 重置第一个雨滴的属性
                raindrop1.size = mergedSize;
                raindrop1.opacity = mergedOpacity;
                raindrops.splice(j, 1); // 移除第二个雨滴
                j--;
            }
        }
    }
}

// 动画循环
function animate() {
    clearCanvas();

    // 创建新的雨滴
    if (Math.random() < 0.1) {
        createRaindrop();
    }

    // 更新并绘制雨滴粒子
    updateRaindrops();
    drawRaindrops();

    // 绘制雨滴之间的融合效果
    drawMerging();

    // 循环调用动画函数
    requestAnimationFrame(animate);
}

// 启动动画
animate();
