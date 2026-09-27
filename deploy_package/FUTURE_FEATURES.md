# Future Features Pending Implementation

## 1. AI-Powered Semantic Similarity Checker for Proposals
**Status:** Pending (To be implemented at the end after core features are finalized)

### Overview
A feature to prevent plagiarism and duplicate projects by comparing new student proposals against all previously approved projects. 

Instead of basic keyword matching (which fails to catch rewording), this system will use an External AI API (like Google Gemini) to understand the underlying **meaning and context** of the proposals.

### How it will work:
1. **Trigger:** When a student submits a new project proposal.
2. **Action:** The PHP backend gathers the new proposal and the titles/descriptions of existing projects.
3. **API Call:** The portal sends a quick API request to an AI provider. 
4. **AI Analysis:** The AI evaluates the semantic similarity of the core concepts (e.g., recognizing that "University AI Chatbot" and "Intelligent Messaging System for Campus" are the same fundamental idea).
5. **Result:** The AI returns a "Similarity Score" (e.g., 15%) and a brief justification (e.g., "The new proposal focuses on AI recommendations rather than just answering FAQs like the 2023 project").
6. **UI Display:** Supervisors and HODs will see this AI Similarity Badge when reviewing the proposal to help them make an informed decision.

### Infrastructure & Performance Notes:
* **No Server Hangs:** Because the heavy Natural Language Processing (NLP) is handled by the external API's supercomputers, the InfinityFree server will not experience high CPU load or crash. It will remain lightweight.
* **Requirements:** We will need to generate and configure a free API Key (e.g., Google Gemini API) when implementing this feature.
