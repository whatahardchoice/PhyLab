# 用户需求分析文档

版本号:v1.0.2

修订历史：

版本号 | 修改说明 
---- | ----
v1.0.0 | 用户需求分析初稿，完成用户场景分析，市场与竞争部分
v1.0.1 | 添加问卷调查结果分析，完善市场与竞争部分
v1.0.2 | 添加用户场景分析，最后确定需求功能

##目录：

- [1.用户需求分析](#1用户需求分析)
	- [1.1. 调查问卷(User Survey)](#11-调查问卷user-survey)
	- [1.2. 项目创新点与收益(Approach and Benefit)](#12-项目创新点与收益Approach-and-Benefit)
		- [创新点](#创新点)
		- [收益](#收益)
	- [1.3. 市场与竞争(Competitors)](#13-市场与竞争competitors)
	       - [市场分析](#市场分析)
	       - [竞争](#竞争)
	      
## 1.用户需求分析

### 1.1. 调查问卷(User Survey)

本次调查我们共采集有效样本93份，采集到的数据符合我们的[]提领，下面是我们的调查数据结果。

![调查问卷问题1](http://git.oschina.net/SivilTaram/Phylab-Web/raw/master/docs/Images/Survey_Problem1_table.png)

我们要建设网站的初衷就是我们体会到了物理实验数据处理的繁琐，所以首先我们设置了问题如上。经过调查结果表示，对于**物理实验数据的处理**认为繁琐的同学占比`93.55%`，称得上是同学中的**绝大部分**。

![调查问卷问题3](http://git.oschina.net/SivilTaram/Phylab-Web/raw/c275346c09ac2d0b3ca7588c00bdc073d2d6938d/docs/Images/Survey_Problem3_table.png)

我们第3个问题所问是关乎网站本身的需求，在这里我们发现，在第1题选`是`的同学中，有`78.16%`的同学希望网站帮他们处理数据。

下面是针对我们网站功能方面的一些调查：

![问题4](http://git.oschina.net/SivilTaram/Phylab-Web/raw/d219e48505d147c81a427078946a01ce8b9fdf28/docs/Images/Survey_Problem2_TestExe.png)

问题4反应了大家对我们网站**杀手级功能**的展示形式的心理预期。有`90%`的同学希望能有中间计算过程的展示，而不是单一地只形成实验报告，那么我们核心功能的方向就定位在**动态展示计算过程**并生成实验报告结果。

![问题6](http://git.oschina.net/SivilTaram/Phylab-Web/raw/c275346c09ac2d0b3ca7588c00bdc073d2d6938d/docs/Images/Survey_Problem6_Circle.png)

在问卷调查的最后一个问题中，我们提到了一个敏感的话题`老师`。

![](https://raw.githubusercontent.com/buaase/Phylab-Web/master/docs/Images/Survey_Problem10_table.png)

问卷调查的结果显示，在网站只支持生成物理实验报告的情况下，在第1题答案为`希望使用网站`与第3题答案为`希望网站帮助计算数据`的同学中，有`58%`的同学认为老师不会支持这样的网站，而`42%`的同学认为老师会支持这样的网站。

所以我们的项目实际上面临着巨大的风险，这样的风险可能来源于学校的阻碍，为此我们在β版本中计划论坛有`老师`角色的加入，让论坛同时也可以作为答疑平台使用，同时对生成的物理实验报告略作一些细节上的调整，加入很多中间计算过程，便利同学的同时也帮助大家理解实验过程。

在产品需求初稿中，我们本来认为用户是不需要**论坛**的，因为我们要做的是个工具网站。现在发现我们的定位应该是容纳**论坛**和**工具**两大部分，通过本次调查的分析，我们确定了α版本和β版本的两个核心主题。α版本核心功能是`工具`，而β版本则需要增加`交流平台`的功能。

### 1.2. 项目创新点与收益(Approach and Benefit)

#### 创新点

为了解决用户的痛点，本项目计划分阶段完成如下四个核心功能：

- 规范且标准的物理预习报告的生成
- 嵌入数据的、计算及步骤正确的物理实验报告的生成
- 手机端自适应帮助查算数据正确性
- 帮助老师和学生进行交流的平台

其中前两个功能将在α版本实现，后两个功能将在β版本实现。

#### 收益

在调查中，我们针对第1题答`是`的同学进行了调查，调查其处理物理实验数据所平均花费的时间，排除掉一些不合理答案后，我们发现：
- 60%的同学花费在1~3小时之间（根据实验难度有不同改变）；
- 20%的同学花费约0.5小时左右；
- 10%的同学花费半天左右；(半天是指一上午,不是12小时)
剩余同学花费时间数据不可靠暂时未计入有效数据。

那么处理物理实验数据花费时间的期望为 `(0.6 * 1.5 + 0.2 * 0.5 + 0.1 * 4)/0.9 = 1.55`。

北航一学期使用网站的期望人数大约为500人左右。（估算凭据参见下方：市场与竞争）

所以，假设我们只针对北航的同学展开物理实验平台的开放，那么平均节省大家的时间也是非常可观的，约有1.55 * 500 = 775个小时，物理实验一周一选，我们以每位同学选够5个实验为标准，那么平均一个学期可以节省同学们至少3875个小时，按一天学习时长为12小时算，足足可以空出323天的额外学习时间。

由此可见本项目为学生节省的时间成本非常高，而时间成本的节省是对用户的最大益处。

### 1.3. 市场与竞争(Competitors)

#### 市场分析

![物理实验人数估计图](http://git.oschina.net/SivilTaram/Phylab-Web/raw/master/docs/Images/PhysicsPersonCount.png)

上面这张图是2013年北航物理实验期末考试的班级安排表，按照一个小班`30人`的规模估计，2012年北航上物理实验的同学有`30 * 100 = 3000`人。

如果我们的宣传到位，能够让20%左右的同学知道，也有将近500位同学会有处理数据方面的需求。何况并不是只有北航有全校范围内的物理实验——据我们调查，`大连理工大学`,`西安电子科技大学`，`西北工业大学`等多个工科为主的大学都有物理实验，并且这些物理实验内容很相似，口说无凭，下面是我们团队的项目经理从大连理工大学同学手里得到的物理实验报告：
[链接](http://pan.baidu.com/share/link?shareid=3471958914&uk=2621436342)

在物理实验报告中，多个实验与北航的物理实验名字相近或者几乎一致：

大连理工 | 北航
----- | ----- 
示波器的原理与使用 | 模拟示波器的使用(必做)
拉伸法测弹性模量 | 拉伸法测钢丝弹性模量
光的等厚干涉 | 劈尖干涉
迈克尔孙和法珀两用干涉仪的调节和使用 | 迈克尔逊干涉（必做）
分光计的调节和介质折射率的测量 | 分光仪的调整(必做),最小偏向角法测量棱镜的折射率

从上述综合来看，我们的用户市场是十分广阔的。

综合上面的调查发现，我们的物理实验网站是很有必要实施的项目，并且是符合大众心理预期的。

#### 竞争

我们分析了许多产品，包括[北航基础物理实验选课网](http://phylab.buaa.edu.cn)，百度文库以及一些历年学长学姐留下来的宝贵的实验预习资料。这些产品各有特色，下面说一些可能和我们项目有关的竞争点：

- 基础物理实验选课网是学校官方开发的网站，学生们可以在上面下载一些预习课件。

- 百度文库是百度的一款产品，文库上有一些常见物理实验的预习和实验报告模板。

- 学长学姐留下来的预习资料是照片形式的实验报告资料，照片清晰，可信度较高。

这些产品与我们项目有关的特点如上所示，下面就逐一分析一下各个产品本身相对我们的项目存在的缺陷：

- 基础物理实验选课网上可提供的预习资料十分之少，一共有5个实验的预习PPT可供下载。并且PPT内容繁杂，不容易把握重点，也不好组织实验报告的撰写形式。

- 百度文库相比我们项目最大的劣势就是：太杂乱。文库是面向大众的，是由网友上传文档提供给大家查看与下载；而我们的项目面向客户暂时只是北航大二学生，所以我们提供的物理实验预习报告都是由我们经过反复确认，并且经过工作人员精心校对和修改后的模板。所以在正确率和符合北航物理实验需求上，我们项目有着`专且精`的优势。

- 学长学姐为我们留下来的预习报告资料可信度很高，但是本身学长学姐的预习报告质量也参差不齐(预习报告老师要打分)，学生们需要付出一定的时间筛选较好的模板用于预习报告。并且由于北航站内未来花园的关闭，预习报告的传承在一定程度上受到了阻碍，相比而言我们项目所制作的实验报告可以通过网站一直传承下去。

上述产品与我们在实验预习报告方面虽有竞争，但我们的定制实验报告的功能是独一家，目前还没有发现能够根据用户数据定制物理实验报告的网站。

我们分析后觉得可能是因为如下三点原因目前还没有人开发类似项目：

- 面向的用户范围较窄
- 盈利模式不明确
- 制作正确的物理实验报告工作量过大

关于第一点，我们面向的群体是一个特殊的群体：高校中有较难物理实验的那些同学(这里是指α版本)，项目本身的属性决定了面向用户的范围较窄。但是用户市场却是十分广阔的，光北航就每学期将近有3000人要上物理实验，更何况全国高校呢？

关于第二点，我们目前没有打算过盈利收入。项目的初衷就是为了方便同学们做物理实验，我们将挂上自愿捐助的支付宝二维码，但不会强制收费。

关于第三点，要做成正确的物理实验报告，工作量确实非常大，并且还涉及到非常多的图表、公式。工作量大的问题将由项目经理通过合理的进度安排来解决。